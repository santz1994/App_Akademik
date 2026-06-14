<?php

namespace App\Support;

use App\Models\RewardPunishment;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LaporanScoreCalculator
{
    public static function resolveKategoriUntukKaryawan(Collection $kategoriList, mixed $karyawan): Collection
    {
        if (! $karyawan) {
            return $kategoriList->values();
        }

        // Check if karyawan has any pangkalan (via pivot table or single field)
        $hasPangkalan = false;
        if (method_exists($karyawan, 'getAllPangkalanIds')) {
            $hasPangkalan = ! empty($karyawan->getAllPangkalanIds());
        } else {
            $hasPangkalan = ! empty($karyawan->pangkalan_id);
        }

        if (! $hasPangkalan) {
            return $kategoriList->values();
        }

        $mappedKategoriIds = self::resolveMappedKategoriIdsByPangkalan($karyawan);

        // Kinerja: filter by pangkalan mapping
        $kategoriKinerja = $kategoriList
            ->filter(fn ($kategori) => strtolower((string) ($kategori->jenis ?? '')) === 'kinerja')
            ->values();

        $selectedKinerja = $mappedKategoriIds->isNotEmpty()
            ? $kategoriKinerja->filter(fn ($kategori) => $mappedKategoriIds->contains((int) $kategori->id))->values()
            : collect();

        // Kegiatan: only include kegiatan mapped to the karyawan's pangkalan(s)
        $kategoriKegiatan = $kategoriList
            ->filter(fn ($kategori) => strtolower((string) ($kategori->jenis ?? '')) === 'kegiatan')
            ->values();

        $selectedKegiatan = $mappedKategoriIds->isNotEmpty()
            ? $kategoriKegiatan->filter(fn ($kategori) => $mappedKategoriIds->contains((int) $kategori->id))->values()
            : collect();

        return $selectedKinerja
            ->concat($selectedKegiatan)
            ->unique('id')
            ->values();
    }

    public static function kompetensiIdsFromKategori(Collection $kategoriList): Collection
    {
        return $kategoriList
            ->flatMap(fn ($kategori) => $kategori->kompetensi->pluck('id'))
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();
    }

    public static function calculate(
        Collection $kategoriList,
        Collection $trxByKompetensi,
        string $method = 'weighted_kategori',
        array $options = []
    ): ?float {
        $hasAnyValue = $trxByKompetensi->contains(fn ($t) => self::isScored($t?->nilai));
        if (! $hasAnyValue) {
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
    ): ?float {
        $kinerjaScore = self::calculateWeightedByKategori(
            $kategoriList->filter(fn ($k) => strtolower((string) ($k->jenis ?? '')) === 'kinerja')->values(),
            $trxByKompetensi
        );

        $kegiatanScore = self::calculateWeightedByKategori(
            $kategoriList->filter(fn ($k) => strtolower((string) ($k->jenis ?? '')) === 'kegiatan')->values(),
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

        // Always apply full weights, even if one component has no values
        // Formula: 70% * kinerjaAvg + 30% * kegiatanAvg
        if ($kinerjaScore !== null) {
            $weightedSum += ($bobotKinerja * $kinerjaScore);
        }
        if ($kegiatanScore !== null) {
            $weightedSum += ($bobotKegiatan * $kegiatanScore);
        }

        // If neither has values, return null
        if ($kinerjaScore === null && $kegiatanScore === null) {
            return null;
        }

        // Always divide by total weight (100%), not just used weight
        $totalWeight = $bobotKinerja + $bobotKegiatan;
        if ($totalWeight <= 0.0) {
            return null;
        }

        return $weightedSum / $totalWeight;
    }

    private static function calculateWeightedByKategori(Collection $kategoriList, Collection $trxByKompetensi): ?float
    {
        $kategoriAverages = [];

        foreach ($kategoriList as $kategori) {
            $values = [];
            foreach ($kategori->kompetensi as $kompetensi) {
                // Try composite key first (kompetensi_id:kategori_kinerja_id), fallback to kompetensi_id
                $compositeKey = (int) $kompetensi->id.':'.(int) $kategori->id;
                $transaksi = $trxByKompetensi->get($compositeKey) ?? $trxByKompetensi->get($kompetensi->id);
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
        } elseif (! empty($karyawan->pangkalan_id)) {
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
                        $pangkalan->kategoriKinerja->pluck('id')->map(fn ($id) => (int) $id)
                    );
                }
            }
        }

        // Fallback: also check single pangkalan relation
        if ($mappedKategoriIds->isEmpty() && $karyawan->relationLoaded('pangkalan')) {
            $pangkalan = $karyawan->pangkalan;
            if ($pangkalan && $pangkalan->relationLoaded('kategoriKinerja')) {
                $mappedKategoriIds = $mappedKategoriIds->merge(
                    $pangkalan->kategoriKinerja->pluck('id')->map(fn ($id) => (int) $id)
                );
            }
        }

        // Final fallback: query database
        if ($mappedKategoriIds->isEmpty() && ! empty($allPangkalanIds)) {
            if (! Schema::hasTable('pangkalan_kategori_kinerja')) {
                return collect();
            }

            $mappedKategoriIds = DB::table('pangkalan_kategori_kinerja')
                ->whereIn('pangkalan_id', $allPangkalanIds)
                ->pluck('kategori_kinerja_id')
                ->map(fn ($id) => (int) $id);
        }

        return $mappedKategoriIds->unique()->values();
    }

    /**
     * Calculate scores broken down by pangkalan for a karyawan.
     * Returns an array with per-pangkalan scores and the averaged final score.
     *
     * @param  Collection  $kategoriList  All kategori (kinerja + kegiatan)
     * @param  Collection  $trxByKompetensi  Transaction records keyed by kompetensi_id
     * @param  array  $allPangkalanIds  All pangkalan IDs the karyawan is assigned to
     * @param  Collection  $pangkalanList  Pangkalan models with kategoriKinerja loaded
     * @param  array  $options  ['bobot_kinerja' => 70, 'bobot_kegiatan' => 30]
     * @return array [
     *               'perPangkalan' => [[ 'pangkalan' => Pangkalan, 'kinerjaAvg' => float|null, 'kategoriDetails' => [...] ], ...],
     *               'kegiatanAvg' => float|null,
     *               'kinerjaFinal' => float|null,  // average of per-pangkalan kinerja averages
     *               'nilaiAkhir' => float|null,
     *               ]
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
            ->filter(fn ($k) => strtolower((string) ($k->jenis ?? '')) === 'kinerja')
            ->values();
        $kegiatanKategori = $kategoriList
            ->filter(fn ($k) => strtolower((string) ($k->jenis ?? '')) === 'kegiatan')
            ->values();

        // Build per-pangkalan kinerja breakdown
        // FIX: Pre-load all pangkalan_kategori_kinerja mappings in one query to avoid N+1
        $allPangkalanKategoriMap = [];
        $needFallback = false;
        foreach ($allPangkalanIds as $pId) {
            $pangkalan = $pangkalanList->first(fn ($p) => (int) $p->id === (int) $pId);
            if (! $pangkalan || ! $pangkalan->relationLoaded('kategoriKinerja')) {
                $needFallback = true;
                break;
            }
        }
        if ($needFallback && Schema::hasTable('pangkalan_kategori_kinerja')) {
            $allMappings = DB::table('pangkalan_kategori_kinerja')
                ->whereIn('pangkalan_id', $allPangkalanIds)
                ->get()
                ->groupBy('pangkalan_id');
            foreach ($allMappings as $pId => $rows) {
                $allPangkalanKategoriMap[(int) $pId] = $rows
                    ->pluck('kategori_kinerja_id')
                    ->map(fn ($id) => (int) $id)
                    ->unique()
                    ->values();
            }
        }

        $perPangkalan = [];
        foreach ($allPangkalanIds as $pId) {
            $pIdInt = (int) $pId;
            $pangkalan = $pangkalanList->first(fn ($p) => (int) $p->id === $pIdInt);

            // Get kategori_kinerja IDs mapped to this pangkalan
            $mappedKategoriIds = collect();
            if ($pangkalan && $pangkalan->relationLoaded('kategoriKinerja')) {
                $mappedKategoriIds = $pangkalan->kategoriKinerja
                    ->pluck('id')
                    ->map(fn ($id) => (int) $id)
                    ->unique()
                    ->values();
            } elseif (isset($allPangkalanKategoriMap[$pIdInt])) {
                // Use pre-loaded mappings instead of N+1 query
                $mappedKategoriIds = $allPangkalanKategoriMap[$pIdInt];
            }

            // Filter kinerja kategori that are mapped to this pangkalan
            $pangkalanKinerjaKategori = $mappedKategoriIds->isNotEmpty()
                ? $kinerjaKategori->filter(fn ($kat) => $mappedKategoriIds->contains((int) $kat->id))->values()
                : collect();

            // Calculate average per kategori for this pangkalan
            // Use per-pangkalan transaksi only (no fallback to global to prevent cross-pangkalan data leak)
            $pangkalanTrx = $trxByPangkalan[$pIdInt] ?? collect();

            $kategoriDetails = [];
            $kategoriAverages = [];
            foreach ($pangkalanKinerjaKategori as $kat) {
                $values = [];
                foreach ($kat->kompetensi as $komp) {
                    $compositeKey = (int) $komp->id.':'.(int) $kat->id;
                    $t = $pangkalanTrx->get($compositeKey) ?? $pangkalanTrx->get($komp->id);
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

            // Also build kegiatan details for this pangkalan (only mapped kegiatan)
            $pangkalanKegiatanKategori = $mappedKategoriIds->isNotEmpty()
                ? $kegiatanKategori->filter(fn ($kat) => $mappedKategoriIds->contains((int) $kat->id))->values()
                : collect();
            $kegiatanDetails = [];
            $kegiatanAverages = [];
            foreach ($pangkalanKegiatanKategori as $kat) {
                $values = [];
                foreach ($kat->kompetensi as $komp) {
                    $compositeKey = (int) $komp->id.':'.(int) $kat->id;
                    $t = $pangkalanTrx->get($compositeKey) ?? $pangkalanTrx->get($komp->id);
                    if ($t && self::isScored($t->nilai)) {
                        $values[] = (float) $t->nilai;
                    }
                }
                $avg = count($values) > 0 ? array_sum($values) / count($values) : null;
                $kegiatanDetails[] = [
                    'kategori' => $kat,
                    'average' => $avg,
                    'kompetensiCount' => count($values),
                ];
                if ($avg !== null) {
                    $kegiatanAverages[] = $avg;
                }
            }

            $pangkalanKinerjaAvg = count($kategoriAverages) > 0
                ? array_sum($kategoriAverages) / count($kategoriAverages)
                : null;

            // Combine kinerja and kegiatan kategori details for this pangkalan
            $allKategoriDetails = array_merge($kategoriDetails, $kegiatanDetails);

            $perPangkalan[] = [
                'pangkalan' => $pangkalan,
                'pangkalan_id' => $pIdInt,
                'kinerjaAvg' => $pangkalanKinerjaAvg,
                'kategoriDetails' => $allKategoriDetails,
                'kegiatanDetails' => $kegiatanDetails,
            ];
        }

        // Calculate kegiatan average (not per-pangkalan, it's global)
        $kegiatanValues = [];
        foreach ($kegiatanKategori as $kat) {
            foreach ($kat->kompetensi as $komp) {
                $compositeKey = (int) $komp->id.':'.(int) $kat->id;
                $t = $trxByKompetensi->get($compositeKey) ?? $trxByKompetensi->get($komp->id);
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
            ->filter(fn ($v) => $v !== null)
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

    /**
     * Get reward/punishment info for a given final score.
     *
     * @return array|null ['grade' => 'C', 'items' => Collection]
     */
    public static function getRewardPunishmentInfo(?float $nilaiAkhir): ?array
    {
        if ($nilaiAkhir === null) {
            return null;
        }

        // Determine grade
        if ($nilaiAkhir >= 90) {
            $grade = 'A';
        } elseif ($nilaiAkhir >= 80) {
            $grade = 'B';
        } elseif ($nilaiAkhir >= 70) {
            $grade = 'C';
        } elseif ($nilaiAkhir >= 60) {
            $grade = 'D';
        } else {
            $grade = 'E';
        }

        // Try to load from database
        try {
            if (Schema::hasTable('reward_punishment')) {
                $items = RewardPunishment::active()
                    ->forGrade($grade)
                    ->get();

                if ($items->isNotEmpty()) {
                    return [
                        'grade' => $grade,
                        'items' => $items,
                    ];
                }
            }
        } catch (\Throwable $e) {
            // Table might not exist yet
        }

        // Fallback: hardcoded defaults
        $defaults = [
            'C' => [
                ['nama' => 'Hukuman Nilai C', 'jumlah' => 5, 'satuan' => 'Sak Semen', 'deskripsi' => 'Karyawan yang mendapatkan nilai C mendapatkan hukuman 5 Sak Semen.'],
            ],
            'D' => [
                ['nama' => 'Hukuman Nilai D', 'jumlah' => 10, 'satuan' => 'Sak Semen', 'deskripsi' => 'Karyawan yang mendapatkan nilai D mendapatkan hukuman 10 Sak Semen.'],
            ],
        ];

        if (isset($defaults[$grade])) {
            return [
                'grade' => $grade,
                'items' => collect($defaults[$grade]),
            ];
        }

        return null;
    }

    /**
     * Calculate final score for a karyawan using the per-pangkalan approach.
     * This ensures consistent results between laporan keseluruhan and perorangan.
     *
     * @param  Collection  $kategoriList  Resolved kategori list with kompetensi loaded
     * @param  mixed  $karyawan  Karyawan model with pangkalans.kategoriKinerja and transaksi loaded
     * @param  array  $options  ['bobot_kinerja' => 70, 'bobot_kegiatan' => 30]
     * @return array ['nilaiAkhir' => float|null, 'kinerjaFinal' => float|null, 'kegiatanAvg' => float|null]
     */
    public static function calculateNilaiAkhirForKaryawan(
        Collection $kategoriList,
        mixed $karyawan,
        array $options = []
    ): array {
        $allPangkalanIds = $karyawan->getAllPangkalanIds();
        $allPangkalan = $karyawan->pangkalans;

        // Build composite-keyed trxByKompetensi with enrichment
        $trxByKompetensi = $karyawan->transaksi
            ->filter(fn ($t) => $t->nilai !== null && in_array((int) ($t->pangkalan_id ?? 0), $allPangkalanIds, true))
            ->mapWithKeys(fn ($t) => [(int) $t->kompetensi_id.':'.(int) ($t->kategori_kinerja_id ?? 0) => $t]);

        $trxByKompetensi = self::enrichTrxForSharedKompetensi($trxByKompetensi, $kategoriList);

        // Build per-pangkalan transaksi maps
        $trxByPangkalan = [];
        foreach ($allPangkalanIds as $pId) {
            $pIdInt = (int) $pId;
            $trxByPangkalan[$pIdInt] = self::enrichTrxForSharedKompetensi(
                $karyawan->transaksi
                    ->filter(fn ($t) => $t->nilai !== null && (int) ($t->pangkalan_id ?? 0) === $pIdInt)
                    ->mapWithKeys(fn ($t) => [(int) $t->kompetensi_id.':'.(int) ($t->kategori_kinerja_id ?? 0) => $t]),
                $kategoriList
            );
        }

        $result = self::calculatePerPangkalan(
            $kategoriList,
            $trxByKompetensi,
            $allPangkalanIds,
            $allPangkalan,
            $options,
            $trxByPangkalan
        );

        return [
            'nilaiAkhir' => $result['nilaiAkhir'],
            'kinerjaFinal' => $result['kinerjaFinal'],
            'kegiatanAvg' => $result['kegiatanAvg'],
        ];
    }

    /**
     * Enrich trxByKompetensi so that a shared kompetensi (belonging to multiple kategoris)
     * has an entry under every kategori it belongs to, using the same transaksi score.
     *
     * This fixes the case where "Pengambilan Keputusan" appears under both "Karakter" and
     * "Kompetensi", but transaksi only stores kategori_kinerja_id for one of them.
     *
     * @param  Collection  $trxByKompetensi  Keyed by "kompetensi_id:kategori_kinerja_id"
     * @param  Collection  $kategoriList  Resolved kategori list with kompetensi loaded
     * @return Collection Enriched collection with additional composite-key entries
     */
    public static function enrichTrxForSharedKompetensi(
        Collection $trxByKompetensi,
        Collection $kategoriList
    ): Collection {
        $enriched = clone $trxByKompetensi;

        // Build a lookup: kompetensi_id => [transaksi, ...]
        $trxByKompId = [];
        foreach ($enriched as $key => $trx) {
            $parts = explode(':', (string) $key);
            $kompId = (int) ($parts[0] ?? 0);
            if ($kompId > 0) {
                $trxByKompId[$kompId] = $trx;
            }
        }

        // For each kategori, for each kompetensi, ensure a composite-key entry exists
        foreach ($kategoriList as $kat) {
            $katId = (int) $kat->id;
            foreach ($kat->kompetensi as $komp) {
                $kompId = (int) $komp->id;
                $compositeKey = $kompId.':'.$katId;
                if (! $enriched->has($compositeKey) && isset($trxByKompId[$kompId])) {
                    $enriched->put($compositeKey, $trxByKompId[$kompId]);
                }
            }
        }

        return $enriched;
    }
}
