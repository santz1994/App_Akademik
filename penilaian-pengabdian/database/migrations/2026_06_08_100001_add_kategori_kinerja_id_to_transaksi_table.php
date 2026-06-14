<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            if (! Schema::hasColumn('transaksi', 'kategori_kinerja_id')) {
                $table->foreignId('kategori_kinerja_id')->nullable()->after('kompetensi_id')
                    ->constrained('kategori_kinerja')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['kategori_kinerja_id']);
            $table->dropColumn('kategori_kinerja_id');
        });
    }
};
