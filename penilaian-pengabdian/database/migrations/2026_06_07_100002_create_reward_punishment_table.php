<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reward_punishment', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique();
            $table->enum('tipe', ['reward', 'punishment']);
            $table->string('grade', 10); // A, B, C, D, E
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->string('satuan')->nullable(); // Sak Semen, dll
            $table->integer('jumlah')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Seed default data
        DB::table('reward_punishment')->insert([
            [
                'kode' => 'RP-001',
                'tipe' => 'punishment',
                'grade' => 'C',
                'nama' => 'Hukuman Nilai C',
                'deskripsi' => 'Karyawan yang mendapatkan nilai akhir C (Cukup) mendapatkan hukuman berupa pengurangan tunjangan.',
                'satuan' => 'Sak Semen',
                'jumlah' => 5,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'RP-002',
                'tipe' => 'punishment',
                'grade' => 'D',
                'nama' => 'Hukuman Nilai D',
                'deskripsi' => 'Karyawan yang mendapatkan nilai akhir D (Kurang) mendapatkan hukuman berupa pengurangan tunjangan.',
                'satuan' => 'Sak Semen',
                'jumlah' => 10,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'RP-003',
                'tipe' => 'reward',
                'grade' => 'A',
                'nama' => 'Reward Nilai A',
                'deskripsi' => 'Karyawan yang mendapatkan nilai akhir A (Sangat Baik) mendapatkan reward berupa bonus kinerja.',
                'satuan' => null,
                'jumlah' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'RP-004',
                'tipe' => 'reward',
                'grade' => 'B',
                'nama' => 'Reward Nilai B',
                'deskripsi' => 'Karyawan yang mendapatkan nilai akhir B (Baik) mendapatkan reward berupa apresiasi.',
                'satuan' => null,
                'jumlah' => 0,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('reward_punishment');
    }
};
