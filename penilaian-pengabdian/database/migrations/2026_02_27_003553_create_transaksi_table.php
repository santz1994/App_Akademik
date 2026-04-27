<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_transaksi', 20)->unique();
            $table->foreignId('karyawan_id')->constrained('karyawan')->cascadeOnDelete();
            $table->foreignId('tahun_penilaian_id')->nullable()->constrained('tahun_penilaian')->nullOnDelete();
            $table->foreignId('kompetensi_id')->nullable()->constrained('kompetensi')->nullOnDelete();
            $table->foreignId('performance_rating_id')->nullable()->constrained('performance_rating')->nullOnDelete();
            $table->decimal('nilai', 5, 2)->nullable();
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};
