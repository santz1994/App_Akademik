<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncTransaksiPangkalan extends Command
{
    protected $signature = 'sync:transaksi-pangkalan';

    protected $description = 'Sync existing transaksi with pangkalan_id from karyawan';

    public function handle(): int
    {
        $updated = DB::update('
            UPDATE transaksi t
            JOIN karyawan k ON k.id = t.karyawan_id
            SET t.pangkalan_id = k.pangkalan_id
            WHERE t.pangkalan_id IS NULL AND k.pangkalan_id IS NOT NULL
        ');

        $remaining = DB::table('transaksi')->whereNull('pangkalan_id')->count();

        $this->info("Updated: {$updated} transaksi. Remaining without pangkalan: {$remaining}");

        return Command::SUCCESS;
    }
}
