<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setting_lembaga', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lembaga')->nullable();
            $table->string('nama_yayasan')->nullable();
            $table->string('nama_ketua_yayasan')->nullable();
            $table->string('nama_ketua_babinlumni')->nullable();

            $table->string('logo_path')->nullable();
            $table->string('ttd_ketua_yayasan_path')->nullable();
            $table->string('ttd_ketua_babinlumni_path')->nullable();

            $table->boolean('show_logo')->default(true);
            $table->boolean('show_tahun_ajaran')->default(true);
            $table->boolean('show_nama_pimpinan')->default(true);
            $table->boolean('show_tanda_tangan')->default(true);

            $table->foreignId('tahun_penilaian_id')
                ->nullable()
                ->constrained('tahun_penilaian')
                ->nullOnDelete();

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setting_lembaga');
    }
};
