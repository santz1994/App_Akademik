<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mutasi', function (Blueprint $table) {
            $table->id();
            $table->string('kode_mutasi', 20)->unique();
            $table->foreignId('karyawan_id')->constrained('karyawan')->cascadeOnDelete();
            $table->foreignId('tahun_penilaian_id')->nullable()->constrained('tahun_penilaian')->nullOnDelete();
            $table->string('jenis_mutasi')->default('pindah'); // pindah, keluar, masuk
            $table->text('keterangan')->nullable();
            $table->date('tanggal_mutasi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mutasi');
    }
};
