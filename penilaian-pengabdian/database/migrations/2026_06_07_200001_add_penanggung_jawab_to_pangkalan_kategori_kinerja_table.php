<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pangkalan_kategori_kinerja', function (Blueprint $table) {
            $table->foreignId('penanggung_jawab_user_id')
                  ->nullable()
                  ->after('kategori_kinerja_id')
                  ->constrained('users')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('pangkalan_kategori_kinerja', function (Blueprint $table) {
            $table->dropForeign(['penanggung_jawab_user_id']);
            $table->dropColumn('penanggung_jawab_user_id');
        });
    }
};
