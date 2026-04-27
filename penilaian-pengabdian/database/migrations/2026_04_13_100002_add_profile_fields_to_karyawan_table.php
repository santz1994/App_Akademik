<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->string('nomor_induk', 50)->nullable()->after('nama_karyawan');
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('nomor_induk');
            $table->string('nomor_surat_tugas', 100)->nullable()->after('tugas_khusus');
            $table->date('tanggal_surat_tugas')->nullable()->after('nomor_surat_tugas');

            $table->unique('nomor_induk');
        });
    }

    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropUnique('karyawan_nomor_induk_unique');
            $table->dropColumn([
                'nomor_induk',
                'jenis_kelamin',
                'nomor_surat_tugas',
                'tanggal_surat_tugas',
            ]);
        });
    }
};
