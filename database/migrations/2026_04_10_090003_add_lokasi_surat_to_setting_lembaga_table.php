<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('setting_lembaga', function (Blueprint $table) {
            $table->string('lokasi_surat', 150)
                ->nullable()
                ->after('nama_yayasan');
        });
    }

    public function down(): void
    {
        Schema::table('setting_lembaga', function (Blueprint $table) {
            $table->dropColumn('lokasi_surat');
        });
    }
};
