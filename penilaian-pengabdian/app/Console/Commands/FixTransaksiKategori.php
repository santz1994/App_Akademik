<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixTransaksiKategori extends Command
{
    protected $signature = 'fix:transaksi-kategori';

    protected $description = 'Fix transaksi with NULL kategori_kinerja_id based on pangkalan-kategori mapping';

    public function handle(): int
    {
        $nullCount = DB::table('transaksi')->whereNull('kategori_kinerja_id')->count();

        if ($nullCount === 0) {
            $this->info('Tidak ada transaksi dengan kategori_kinerja_id NULL.');

            return Command::SUCCESS;
        }

        $this->info("Ditemukan {$nullCount} transaksi dengan kategori_kinerja_id NULL.");

        // Build mapping: pangkalan_id => [kompetensi_id => kategori_kinerja_id]
        $pangkalanKategoriMap = [];
        $pangkalanKategori = DB::table('pangkalan_kategori_kinerja')
            ->join('kategori_kinerja_kompetensi', 'kategori_kinerja_kompetensi.kategori_kinerja_id', '=', 'pangkalan_kategori_kinerja.kategori_kinerja_id')
            ->select(
                'pangkalan_kategori_kinerja.pangkalan_id',
                'kategori_kinerja_kompetensi.kompetensi_id',
                'pangkalan_kategori_kinerja.kategori_kinerja_id'
            )
            ->get();

        foreach ($pangkalanKategori as $row) {
            $pId = (int) $row->pangkalan_id;
            $kompId = (int) $row->kompetensi_id;
            $katId = (int) $row->kategori_kinerja_id;

            if (! isset($pangkalanKategoriMap[$pId])) {
                $pangkalanKategoriMap[$pId] = [];
            }
            // If kompetensi belongs to multiple kategori for same pangkalan, prefer kegiatan
            if (! isset($pangkalanKategoriMap[$pId][$kompId])) {
                $pangkalanKategoriMap[$pId][$kompId] = $katId;
            }
        }

        $updated = 0;
        $skipped = 0;

        DB::table('transaksi')
            ->whereNull('kategori_kinerja_id')
            ->orderBy('id')
            ->chunkById(200, function ($rows) use ($pangkalanKategoriMap, &$updated, &$skipped) {
                foreach ($rows as $trx) {
                    $pId = (int) $trx->pangkalan_id;
                    $kompId = (int) $trx->kompetensi_id;

                    if (isset($pangkalanKategoriMap[$pId][$kompId])) {
                        DB::table('transaksi')
                            ->where('id', $trx->id)
                            ->update(['kategori_kinerja_id' => $pangkalanKategoriMap[$pId][$kompId]]);
                        $updated++;
                    } else {
                        $skipped++;
                    }
                }
            });

        $this->info("Berhasil update: {$updated} transaksi.");
        if ($skipped > 0) {
            $this->warn("Dilewati (tidak ada mapping): {$skipped} transaksi.");
        }

        $remaining = DB::table('transaksi')->whereNull('kategori_kinerja_id')->count();
        $this->info("Sisa transaksi tanpa kategori_kinerja_id: {$remaining}");

        return Command::SUCCESS;
    }
}
