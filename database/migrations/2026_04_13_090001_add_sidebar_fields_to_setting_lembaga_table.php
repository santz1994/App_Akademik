<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('setting_lembaga', function (Blueprint $table) {
            $table->string('sidebar_title', 150)
                ->default('Website Aplikasi')
                ->after('nama_yayasan');
            $table->string('sidebar_subtitle_1', 255)
                ->default('Sistem Manajemen Kinerja Pengabdian')
                ->after('sidebar_title');
            $table->string('sidebar_subtitle_2', 255)
                ->default('Yayasan Pondok Pesantren Al-Huda Mugomulyo')
                ->after('sidebar_subtitle_1');
        });
    }

    public function down(): void
    {
        Schema::table('setting_lembaga', function (Blueprint $table) {
            $table->dropColumn([
                'sidebar_title',
                'sidebar_subtitle_1',
                'sidebar_subtitle_2',
            ]);
        });
    }
};
