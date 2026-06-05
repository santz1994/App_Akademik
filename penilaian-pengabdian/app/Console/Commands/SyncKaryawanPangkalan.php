<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Karyawan;
use Illuminate\Support\Facades\DB;

class SyncKaryawanPangkalan extends Command
{
    protected $signature = 'sync:karyawan-pangkalan';
    protected $description = 'Sync existing karyawan pangkalan_id to karyawan_pangkalan pivot table';

    public function handle(): int
    {
        $karyawanList = Karyawan::whereNotNull('pangkalan_id')->get();
        $synced = 0;
        $skipped = 0;

        foreach ($karyawanList as $karyawan) {
            $exists = DB::table('karyawan_pangkalan')
                ->where('karyawan_id', $karyawan->id)
                ->where('pangkalan_id', $karyawan->pangkalan_id)
                ->exists();

            if (!$exists) {
                DB::table('karyawan_pangkalan')->insert([
                    'karyawan_id' => $karyawan->id,
                    'pangkalan_id' => $karyawan->pangkalan_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $synced++;
            } else {
                $skipped++;
            }
        }

        $this->info("Sync selesai. Disync: {$synced}, Sudah ada: {$skipped}, Total karyawan dengan pangkalan: {$karyawanList->count()}");
        return Command::SUCCESS;
    }
}
