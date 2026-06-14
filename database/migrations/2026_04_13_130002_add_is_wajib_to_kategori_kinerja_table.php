<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kategori_kinerja', function (Blueprint $table) {
            $table->boolean('is_wajib')->default(false)->after('jenis');
        });

        DB::table('kategori_kinerja')
            ->where('jenis', 'kegiatan')
            ->where(function ($query) {
                $query->whereRaw('LOWER(kategori) LIKE ?', ['%pramuka%'])
                    ->orWhereRaw('LOWER(kategori) LIKE ?', ['%masjid%']);
            })
            ->update(['is_wajib' => true]);
    }

    public function down(): void
    {
        Schema::table('kategori_kinerja', function (Blueprint $table) {
            $table->dropColumn('is_wajib');
        });
    }
};
