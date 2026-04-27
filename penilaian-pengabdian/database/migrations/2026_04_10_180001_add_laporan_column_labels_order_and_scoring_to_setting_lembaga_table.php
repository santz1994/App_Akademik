<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('setting_lembaga', function (Blueprint $table) {
            $table->text('laporan_column_order')->nullable()->after('laporan_col_width_rating');

            $table->string('laporan_label_no', 100)->default('No')->after('laporan_column_order');
            $table->string('laporan_label_kode_karyawan', 100)->default('Kode Karyawan')->after('laporan_label_no');
            $table->string('laporan_label_nama_karyawan', 100)->default('Nama Karyawan')->after('laporan_label_kode_karyawan');
            $table->string('laporan_label_pangkalan', 100)->default('Pangkalan')->after('laporan_label_nama_karyawan');
            $table->string('laporan_label_detail_kompetensi', 100)->default('Detail Kompetensi')->after('laporan_label_pangkalan');
            $table->string('laporan_label_nilai_akhir', 100)->default('Nilai Akhir')->after('laporan_label_detail_kompetensi');
            $table->string('laporan_label_rating', 100)->default('Rating')->after('laporan_label_nilai_akhir');

            $table->string('laporan_scoring_method', 40)->default('weighted_kategori')->after('laporan_label_rating');
        });
    }

    public function down(): void
    {
        Schema::table('setting_lembaga', function (Blueprint $table) {
            $table->dropColumn([
                'laporan_column_order',
                'laporan_label_no',
                'laporan_label_kode_karyawan',
                'laporan_label_nama_karyawan',
                'laporan_label_pangkalan',
                'laporan_label_detail_kompetensi',
                'laporan_label_nilai_akhir',
                'laporan_label_rating',
                'laporan_scoring_method',
            ]);
        });
    }
};
