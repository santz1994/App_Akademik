<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->foreignId('pangkalan_id')->nullable()->after('tahun_penilaian_id')
                  ->constrained('pangkalan')->nullOnDelete();
            $table->string('tugas_khusus')->nullable()->after('alamat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropForeign(['pangkalan_id']);
            $table->dropColumn(['pangkalan_id', 'tugas_khusus']);
        });
    }
};
