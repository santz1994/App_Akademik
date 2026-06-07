<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Pangkalan;
use App\Models\Karyawan;
use App\Models\User;

$wajibPangkalans = Pangkalan::where('is_wajib', true)->where('is_active', true)->get();

foreach ($wajibPangkalans as $p) {
    echo "=== {$p->nama_pangkalan} (ID: {$p->id}) ===\n";

    // 1. Hapus kepala dari wajib pangkalan
    $kepalaKaryawanIds = Karyawan::whereHas('user', fn($q) => $q->where('is_kepala', true))->pluck('id');
    $removed = 0;
    foreach ($kepalaKaryawanIds as $kid) {
        $k = Karyawan::find($kid);
        if ($k && $k->pangkalans()->where('pangkalan_id', $p->id)->exists()) {
            $k->pangkalans()->detach($p->id);
            $removed++;
            echo "  Removed kepala: {$k->nama_karyawan}\n";
        }
    }
    echo "  Removed {$removed} kepala\n";

    // 2. Sync ke semua karyawan aktif non-kepala
    $karyawanIds = Karyawan::where('is_active', true)
        ->whereDoesntHave('user', fn($q) => $q->where('is_kepala', true))
        ->pluck('id');
    $added = 0;
    foreach ($karyawanIds as $kid) {
        $k = Karyawan::find($kid);
        if ($k && !$k->pangkalans()->where('pangkalan_id', $p->id)->exists()) {
            $k->pangkalans()->attach($p->id);
            $added++;
        }
    }
    echo "  Added {$added} karyawan\n";

    // 3. Count
    $count = $p->fresh()->karyawan_count;
    echo "  Final count: {$count}\n\n";
}

echo "Done!\n";
