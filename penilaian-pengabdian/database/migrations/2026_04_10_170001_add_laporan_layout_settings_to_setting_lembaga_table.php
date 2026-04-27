<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('setting_lembaga', function (Blueprint $table) {
            $table->string('laporan_paper_size', 20)->default('a4')->after('laporan_show_bobot_kategori');
            $table->string('laporan_orientation', 20)->default('portrait')->after('laporan_paper_size');

            $table->decimal('laporan_margin_top', 4, 2)->default(2.54)->after('laporan_orientation');
            $table->decimal('laporan_margin_right', 4, 2)->default(2.54)->after('laporan_margin_top');
            $table->decimal('laporan_margin_bottom', 4, 2)->default(2.54)->after('laporan_margin_right');
            $table->decimal('laporan_margin_left', 4, 2)->default(2.54)->after('laporan_margin_bottom');

            $table->string('laporan_text_align', 20)->default('left')->after('laporan_margin_left');
            $table->string('laporan_header_align', 20)->default('center')->after('laporan_text_align');

            $table->unsignedTinyInteger('laporan_cell_padding')->default(6)->after('laporan_header_align');
            $table->decimal('laporan_border_width', 3, 1)->default(1.0)->after('laporan_cell_padding');
            $table->unsignedTinyInteger('laporan_font_size')->default(11)->after('laporan_border_width');
            $table->unsignedTinyInteger('laporan_title_font_size')->default(16)->after('laporan_font_size');

            $table->unsignedSmallInteger('laporan_col_width_no')->default(32)->after('laporan_title_font_size');
            $table->unsignedSmallInteger('laporan_col_width_kode')->default(72)->after('laporan_col_width_no');
            $table->unsignedSmallInteger('laporan_col_width_nama')->default(190)->after('laporan_col_width_kode');
            $table->unsignedSmallInteger('laporan_col_width_pangkalan')->default(140)->after('laporan_col_width_nama');
            $table->unsignedSmallInteger('laporan_col_width_nilai')->default(88)->after('laporan_col_width_pangkalan');
            $table->unsignedSmallInteger('laporan_col_width_rating')->default(108)->after('laporan_col_width_nilai');
        });
    }

    public function down(): void
    {
        Schema::table('setting_lembaga', function (Blueprint $table) {
            $table->dropColumn([
                'laporan_paper_size',
                'laporan_orientation',
                'laporan_margin_top',
                'laporan_margin_right',
                'laporan_margin_bottom',
                'laporan_margin_left',
                'laporan_text_align',
                'laporan_header_align',
                'laporan_cell_padding',
                'laporan_border_width',
                'laporan_font_size',
                'laporan_title_font_size',
                'laporan_col_width_no',
                'laporan_col_width_kode',
                'laporan_col_width_nama',
                'laporan_col_width_pangkalan',
                'laporan_col_width_nilai',
                'laporan_col_width_rating',
            ]);
        });
    }
};
