<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class LaporanScoreCalculator
{
    public static function resolveKategoriUntukKaryawan(Collection $kategoriList, mixed $karyawan): Collection
    {
        if (!$karyawan) {
            return $kategoriList->values();
        }

        // Check if karyawan has any pangkalan (via pivot table or single field)
        $hasPangkalan = false;
        if (method_exists($karyawan, 'getAllPangkalanIds')) {
            $hasPangkalan = !empty($karyawan->getAllPangkalanIds());
        } else {
            $hasPangkalan = !empty($karyawan->pangkalan_id);
        }

        if (!$hasPangkalan) {
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
            return ['label' => '-', 'color' => 'secondary'];
        }

        if ($nilaiAkhir >= 90) {
            return ['label' => 'A - Sangat Baik', 'color' => 'success'];
        }

        if ($nilaiAkhir >= 80) {
            return ['label' => 'B - Baik', 'color' => 'primary'];
        }

        if ($nilaiAkhir >= 70) {
            return ['label' => 'C - Cukup', 'color' => 'warning'];
        }

        if ($nilaiAkhir >= 60) {
            return ['label' => 'D - Kurang', 'color' => 'danger'];
        }

        return ['label' => 'E - Sangat Kurang', 'color' => 'dark'];
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
        // Get all pangkalan IDs from pivot table (multi-pangkalan support)
        $allPangkalanIds = [];
        if (method_exists($karyawan, 'getAllPangkalanIds')) {
            $allPangkalanIds = $karyawan->getAllPangkalanIds();
        } elseif (!empty($karyawan->pangkalan_id)) {
            $allPangkalanIds = [(int) $karyawan->pangkalan_id];
        }

        if (empty($allPangkalanIds)) {
            return collect();
        }

        // Aggregate kategori IDs from all pangkalans
        $mappedKategoriIds = collect();

        // Try to use loaded relations first
        if ($karyawan->relationLoaded('pangkalans')) {
            foreach ($karyawan->pangkalans as $pangkalan) {
                if ($pangkalan->relationLoaded('kategoriKinerja')) {
                    $mappedKategoriIds = $mappedKategoriIds->merge(
                        $pangkalan->kategoriKinerja->pluck('id')->map(fn($id) => (int) $id)
                    );
                }
            }
        }

        // Fallback: also check single pangkalan relation
        if ($mappedKategoriIds->isEmpty() && $karyawan->relationLoaded('pangkalan')) {
            $pangkalan = $karyawan->pangkalan;
            if ($pangkalan && $pangkalan->relationLoaded('kategoriKinerja')) {
                $mappedKategoriIds = $mappedKategoriIds->merge(
                    $pangkalan->kategoriKinerja->pluck('id')->map(fn($id) => (int) $id)
                );
            }
        }

        // Final fallback: query database
        if ($mappedKategoriIds->isEmpty() && !empty($allPangkalanIds)) {
            if (!Schema::hasTable('pangkalan_kategori_kinerja')) {
                return collect();
            }

            $mappedKategoriIds = DB::table('pangkalan_kategori_kinerja')
                ->whereIn('pangkalan_id', $allPangkalanIds)
                ->pluck('kategori_kinerja_id')
                ->map(fn($id) => (int) $id);
        }

        return $mappedKategoriIds->unique()->values();
    }

    /**
     * Calculate scores broken down by pangkalan for a karyawan.
     * Returns an array with per-pangkalan scores and the averaged final score.
     *
     * @param Collection $kategoriList  All kategori (kinerja + kegiatan)
     * @param Collection $trxByKompetensi  Transaction records keyed by kompetensi_id
     * @param array      $allPangkalanIds  All pangkalan IDs the karyawan is assigned to
     * @param Collection $pangkalanList  Pangkalan models with kategoriKinerja loaded
     * @param array      $options  ['bobot_kinerja' => 70, 'bobot_kegiatan' => 30]
     * @return array  [
     *     'perPangkalan' => [[ 'pangkalan' => Pangkalan, 'kinerjaAvg' => float|null, 'kategoriDetails' => [...] ], ...],
     *     'kegiatanAvg' => float|null,
     *     'kinerjaFinal' => float|null,  // average of per-pangkalan kinerja averages
     *     'nilaiAkhir' => float|null,
     * ]
     */
    public static function calculatePerPangkalan(
        Collection $kategoriList,
        Collection $trxByKompetensi,
        array $allPangkalanIds,
        Collection $pangkalanList,
        array $options = [],
        array $trxByPangkalan = []
    ): array {
        $kinerjaKategori = $kategoriList
            ->filter(fn($k) => strtolower((string) ($k->jenis ?? '')) === 'kinerja')
            ->values();
        $kegiatanKategori = $kategoriList
            ->filter(fn($k) => strtolower((string) ($k->jenis ?? '')) === 'kegiatan')
            ->values();

        // Build per-pangkalan kinerja breakdown
        $perPangkalan = [];
        foreach ($allPangkalanIds as $pId) {
            $pIdInt = (int) $pId;
            $pangkalan = $pangkalanList->first(fn($p) => (int) $p->id === $pIdInt);

            // Get kategori_kinerja IDs mapped to this pangkalan
            $mappedKategoriIds = collect();
            if ($pangkalan && $pangkalan->relationLoaded('kategoriKinerja')) {
                $mappedKategoriIds = $pangkalan->kategoriKinerja
                    ->pluck('id')
                    ->map(fn($id) => (int) $id)
                    ->unique()
                    ->values();
            } elseif (Schema::hasTable('pangkalan_kategori_kinerja')) {
                $mappedKategoriIds = DB::table('pangkalan_kategori_kinerja')
                    ->where('pangkalan_id', $pIdInt)
                    ->pluck('kategori_kinerja_id')
                    ->map(fn($id) => (int) $id)
                    ->unique()
                    ->values();
            }

            // Filter kinerja kategori that are mapped to this pangkalan
            $pangkalanKinerjaKategori = $mappedKategoriIds->isNotEmpty()
                ? $kinerjaKategori->filter(fn($kat) => $mappedKategoriIds->contains((int) $kat->id))->values()
                : $kinerjaKategori;

            // Calculate average per kategori for this pangkalan
            // Use per-pangkalan transaksi if available, otherwise fall back to global
            $pangkalanTrx = $trxByPangkalan[$pIdInt] ?? $trxByKompetensi;

            $kategoriDetails = [];
            $kategoriAverages = [];
            foreach ($pangkalanKinerjaKategori as $kat) {
                $values = [];
                foreach ($kat->kompetensi as $komp) {
                    $t = $pangkalanTrx->get($komp->id);
                    if ($t && self::isScored($t->nilai)) {
                        $values[] = (float) $t->nilai;
                    }
                }
                $avg = count($values) > 0 ? array_sum($values) / count($values) : null;
                $kategoriDetails[] = [
                    'kategori' => $kat,
                    'average' => $avg,
                    'kompetensiCount' => count($values),
                ];
                if ($avg !== null) {
                    $kategoriAverages[] = $avg;
                }
            }

            $pangkalanKinerjaAvg = count($kategoriAverages) > 0
                ? array_sum($kategoriAverages) / count($kategoriAverages)
                : null;

            $perPangkalan[] = [
                'pangkalan' => $pangkalan,
                'pangkalan_id' => $pIdInt,
                'kinerjaAvg' => $pangkalanKinerjaAvg,
                'kategoriDetails' => $kategoriDetails,
            ];
        }

        // Calculate kegiatan average (not per-pangkalan, it's global)
        $kegiatanValues = [];
        foreach ($kegiatanKategori as $kat) {
            foreach ($kat->kompetensi as $komp) {
                $t = $trxByKompetensi->get($komp->id);
                if ($t && self::isScored($t->nilai)) {
                    $kegiatanValues[] = (float) $t->nilai;
                }
            }
        }
        $kegiatanAvg = count($kegiatanValues) > 0
            ? array_sum($kegiatanValues) / count($kegiatanValues)
            : null;

        // Average kinerja across all pangkalan
        $validPangkalanAvgs = collect($perPangkalan)
            ->pluck('kinerjaAvg')
            ->filter(fn($v) => $v !== null)
            ->values();

        $kinerjaFinal = $validPangkalanAvgs->isNotEmpty()
            ? $validPangkalanAvgs->sum() / $validPangkalanAvgs->count()
            : null;

        // Final weighted score
        $nilaiAkhir = null;
        $bobotKinerja = self::normalizedPercent((float) ($options['bobot_kinerja'] ?? 70));
        $bobotKegiatan = self::normalizedPercent((float) ($options['bobot_kegiatan'] ?? 30));

        if ($kinerjaFinal !== null || $kegiatanAvg !== null) {
            $weightedSum = 0.0;
            $usedWeight = 0.0;

            if ($kinerjaFinal !== null) {
                $weightedSum += ($bobotKinerja * $kinerjaFinal);
                $usedWeight += $bobotKinerja;
            }
            if ($kegiatanAvg !== null) {
                $weightedSum += ($bobotKegiatan * $kegiatanAvg);
                $usedWeight += $bobotKegiatan;
            }

            if ($usedWeight > 0.0) {
                $nilaiAkhir = $weightedSum / $usedWeight;
            }
        }

        return [
            'perPangkalan' => $perPangkalan,
            'kegiatanAvg' => $kegiatanAvg,
            'kinerjaFinal' => $kinerjaFinal,
            'nilaiAkhir' => $nilaiAkhir,
        ];
    }
}
