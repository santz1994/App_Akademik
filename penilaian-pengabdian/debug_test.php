<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\KategoriKinerja;
use App\Models\Karyawan;
use App\Support\LaporanScoreCalculator;
use App\Models\Pangkalan;

// Test with karyawan 35 (Ahmad Khoirul Amin) - match controller loading
$karyawan = Karyawan::with([
    'pangkalan.kategoriKinerja',
    'pangkalanLain.kategoriKinerja',
    'transaksi',
    'tahunPenilaian',
    'user',
])->find(35);
echo "Karyawan: {$karyawan->nama_karyawan}\n";
echo "Pangkalan IDs: " . json_encode($karyawan->getAllPangkalanIds()) . "\n\n";

// Load kategori
$kategoriList = KategoriKinerja::with('kompetensi')->orderBy('jenis')->orderBy('kode_kategori')->get();
echo "Total kategori: " . $kategoriList->count() . "\n";
echo "Kinerja: " . $kategoriList->filter(fn($k) => strtolower((string)($k->jenis ?? '')) === 'kinerja')->count() . "\n";
echo "Kegiatan: " . $kategoriList->filter(fn($k) => strtolower((string)($k->jenis ?? '')) === 'kegiatan')->count() . "\n\n";

// Resolve kategori for karyawan
$resolved = LaporanScoreCalculator::resolveKategoriUntukKaryawan($kategoriList, $karyawan);
echo "Resolved kategori count: " . $resolved->count() . "\n";
echo "Resolved kategori IDs: " . json_encode($resolved->pluck('id')->toArray()) . "\n\n";

// Debug resolveMappedKategoriIdsByPangkalan
$reflection = new ReflectionClass(LaporanScoreCalculator::class);
$method = $reflection->getMethod('resolveMappedKategoriIdsByPangkalan');
$method->setAccessible(true);
$mappedIds = $method->invoke(null, $karyawan);
echo "Mapped kategori IDs from resolveMappedKategoriIdsByPangkalan: " . json_encode($mappedIds->toArray()) . "\n\n";

// Get all pangkalan
$allPangkalanIds = $karyawan->getAllPangkalanIds();
$allPangkalan = Pangkalan::with('kategoriKinerja')->whereIn('id', $allPangkalanIds)->get();

echo "All pangkalan with mapped kategori:\n";
foreach ($allPangkalan as $p) {
    $mappedIds = $p->kategoriKinerja->pluck('id')->toArray();
    echo "  {$p->id}: {$p->nama_pangkalan} -> mapped kategori: " . json_encode($mappedIds) . "\n";
}
echo "\n";

// Calculate per-pangkalan
$result = LaporanScoreCalculator::calculatePerPangkalan(
    $resolved,
    collect(),
    $allPangkalanIds,
    $allPangkalan,
    ['bobot_kinerja' => 70, 'bobot_kegiatan' => 30],
    []
);

echo "Per-pangkalan results:\n";
foreach ($result['perPangkalan'] as $pp) {
    echo "  {$pp['pangkalan_id']}: " . ($pp['pangkalan']?->nama_pangkalan ?? 'N/A') . "\n";
    echo "    kategoriDetails count: " . count($pp['kategoriDetails']) . "\n";
    foreach ($pp['kategoriDetails'] as $kd) {
        echo "      - {$kd['kategori']->kategori} ({$kd['kategori']->jenis}), kompetensi: " . $kd['kategori']->kompetensi->count() . "\n";
    }
}
