<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('setting_lembaga', function (Blueprint $table) {
            $table->decimal('laporan_bobot_kinerja', 5, 2)
                ->default(70)
                ->after('laporan_scoring_method');
            $table->decimal('laporan_bobot_kegiatan', 5, 2)
                ->default(30)
                ->after('laporan_bobot_kinerja');
        });
    }

    public function down(): void
    {
        Schema::table('setting_lembaga', function (Blueprint $table) {
            $table->dropColumn([
                'laporan_bobot_kinerja',
                'laporan_bobot_kegiatan',
            ]);
        });
    }
};
