<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class LaporanScoreCalculator
{
    public static function resolveKategoriUntukKaryawan(Collection $kategoriList, mixed $karyawan): Collection
    {
        if (!$karyawan || empty($karyawan->pangkalan_id)) {
            return $kategoriList->values();
        }

        $mappedKategoriIds = self::resolveMappedKategoriIdsByPangkalan($karyawan);

        $kategoriKinerja = $kategoriList
            ->filter(fn($kategori) => strtolower((string) ($kategori->jenis ?? '')) === 'kinerja')
            ->values();

        $selectedKinerja = $mappedKategoriIds->isNotEmpty()
            ? $kategoriKinerja->filter(fn($kategori) => $mappedKategoriIds->contains((int) $kategori->id))->values()
            : $kategoriKinerja;

        $mandatoryKegiatan = $kategoriList
            ->filter(fn($kategori) => strtolower((string) ($kategori->jenis ?? '')) === 'kegiatan' && (bool) ($kategori->is_wajib ?? false))
            ->values();

        return $selectedKinerja
            ->concat($mandatoryKegiatan)
            ->unique('id')
            ->values();
    }

    public static function kompetensiIdsFromKategori(Collection $kategoriList): Collection
    {
        return $kategoriList
            ->flatMap(fn($kategori) => $kategori->kompetensi->pluck('id'))
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();
    }

    public static function calculate(
        Collection $kategoriList,
        Collection $trxByKompetensi,
        string $method = 'weighted_kategori',
        array $options = []
    ): ?float
    {
        $hasAnyValue = $trxByKompetensi->contains(fn($t) => self::isScored($t?->nilai));
        if (!$hasAnyValue) {
            return null;
        }

        if (in_array($method, ['average_kinerja_kegiatan', 'weighted_kinerja_kegiatan'], true)) {
            return self::calculateWeightedByJenis($kategoriList, $trxByKompetensi, $options);
        }

        return self::calculateWeightedByKategori($kategoriList, $trxByKompetensi);
    }

    public static function ratingMeta(?float $nilaiAkhir): array
    {
        if ($nilaiAkhir === null) {
            return ['label' => '-', 'color' => 'secondary', 'bg' => '#e5e7eb', 'text' => '#6b7280'];
        }

        if ($nilaiAkhir >= 90) {
            return ['label' => 'A - Sangat Baik', 'color' => 'success', 'bg' => '#dcfce7', 'text' => '#166534'];
        }

        if ($nilaiAkhir >= 80) {
            return ['label' => 'B - Baik', 'color' => 'primary', 'bg' => '#dbeafe', 'text' => '#1e40af'];
        }

        if ($nilaiAkhir >= 70) {
            return ['label' => 'C - Cukup', 'color' => 'warning', 'bg' => '#fef3c7', 'text' => '#92400e'];
        }

        if ($nilaiAkhir >= 60) {
            return ['label' => 'D - Kurang', 'color' => 'danger', 'bg' => '#fee2e2', 'text' => '#991b1b'];
        }

        return ['label' => 'E - Sangat Kurang', 'color' => 'dark', 'bg' => '#f3f4f6', 'text' => '#374151'];
    }

    private static function calculateWeightedByJenis(
        Collection $kategoriList,
        Collection $trxByKompetensi,
        array $options = []
    ): ?float
    {
        $kinerjaScore = self::calculateWeightedByKategori(
            $kategoriList->filter(fn($k) => strtolower((string) ($k->jenis ?? '')) === 'kinerja')->values(),
            $trxByKompetensi
        );

        $kegiatanScore = self::calculateWeightedByKategori(
            $kategoriList->filter(fn($k) => strtolower((string) ($k->jenis ?? '')) === 'kegiatan')->values(),
            $trxByKompetensi
        );

        if ($kinerjaScore === null && $kegiatanScore === null) {
            return null;
        }

        $bobotKinerja = self::normalizedPercent((float) ($options['bobot_kinerja'] ?? 50));
        $bobotKegiatan = self::normalizedPercent((float) ($options['bobot_kegiatan'] ?? 50));

        if (($bobotKinerja + $bobotKegiatan) <= 0.0) {
            $bobotKinerja = 50.0;
            $bobotKegiatan = 50.0;
        }

        $weightedSum = 0.0;
        $usedWeight = 0.0;

        if ($kinerjaScore !== null) {
            $weightedSum += ($bobotKinerja * $kinerjaScore);
            $usedWeight += $bobotKinerja;
        }
        if ($kegiatanScore !== null) {
            $weightedSum += ($bobotKegiatan * $kegiatanScore);
            $usedWeight += $bobotKegiatan;
        }

        if ($usedWeight <= 0.0) {
            return null;
        }

        return $weightedSum / $usedWeight;
    }

    private static function calculateWeightedByKategori(Collection $kategoriList, Collection $trxByKompetensi): ?float
    {
        $kategoriAverages = [];

        foreach ($kategoriList as $kategori) {
            $values = [];
            foreach ($kategori->kompetensi as $kompetensi) {
                $transaksi = $trxByKompetensi->get($kompetensi->id);
                if ($transaksi && self::isScored($transaksi->nilai)) {
                    $values[] = (float) $transaksi->nilai;
                }
            }

            if (count($values) > 0) {
                $kategoriAverages[] = array_sum($values) / count($values);
            }
        }

        if (count($kategoriAverages) === 0) {
            return null;
        }

        // Formula terbaru: rata-rata dari rata-rata setiap kategori yang memiliki nilai valid.
        return array_sum($kategoriAverages) / count($kategoriAverages);
    }

    private static function isScored(mixed $nilai): bool
    {
        return $nilai !== null && is_numeric($nilai);
    }

    private static function normalizedPercent(float $value): float
    {
        return max(0.0, min(100.0, $value));
    }

    private static function resolveMappedKategoriIdsByPangkalan(mixed $karyawan): Collection
    {
        $pangkalanId = (int) ($karyawan->pangkalan_id ?? 0);
        if ($pangkalanId <= 0) {
            return collect();
        }

        $pangkalan = $karyawan->pangkalan ?? null;
        if ($pangkalan && $pangkalan->relationLoaded('kategoriKinerja')) {
            return $pangkalan->kategoriKinerja
                ->pluck('id')
                ->map(fn($id) => (int) $id)
                ->unique()
                ->values();
        }

        if (!Schema::hasTable('pangkalan_kategori_kinerja')) {
            return collect();
        }

        return DB::table('pangkalan_kategori_kinerja')
            ->where('pangkalan_id', $pangkalanId)
            ->pluck('kategori_kinerja_id')
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();
    }
}
