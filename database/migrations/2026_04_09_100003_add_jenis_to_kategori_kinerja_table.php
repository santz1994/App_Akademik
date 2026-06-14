<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kategori_kinerja', function (Blueprint $table) {
            $table->enum('jenis', ['kinerja', 'kegiatan'])->default('kinerja')->after('kategori');
        });
    }

    public function down(): void
    {
        Schema::table('kategori_kinerja', function (Blueprint $table) {
            $table->dropColumn('jenis');
        });
    }
};
