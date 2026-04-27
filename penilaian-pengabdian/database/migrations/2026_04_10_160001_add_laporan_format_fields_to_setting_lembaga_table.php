<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('setting_lembaga', function (Blueprint $table) {
            $table->enum('laporan_default_jenis', ['ringkas', 'rinci'])
                ->default('ringkas')
                ->after('show_tanda_tangan');

            $table->boolean('laporan_show_no')->default(true)->after('laporan_default_jenis');
            $table->boolean('laporan_show_kode_karyawan')->default(true)->after('laporan_show_no');
            $table->boolean('laporan_show_pangkalan')->default(true)->after('laporan_show_kode_karyawan');
            $table->boolean('laporan_show_nilai_akhir')->default(true)->after('laporan_show_pangkalan');
            $table->boolean('laporan_show_rating')->default(true)->after('laporan_show_nilai_akhir');
            $table->boolean('laporan_show_detail_kompetensi')->default(true)->after('laporan_show_rating');
            $table->boolean('laporan_show_bobot_kategori')->default(true)->after('laporan_show_detail_kompetensi');
        });
    }

    public function down(): void
    {
        Schema::table('setting_lembaga', function (Blueprint $table) {
            $table->dropColumn([
                'laporan_default_jenis',
                'laporan_show_no',
                'laporan_show_kode_karyawan',
                'laporan_show_pangkalan',
                'laporan_show_nilai_akhir',
                'laporan_show_rating',
                'laporan_show_detail_kompetensi',
                'laporan_show_bobot_kategori',
            ]);
        });
    }
};
