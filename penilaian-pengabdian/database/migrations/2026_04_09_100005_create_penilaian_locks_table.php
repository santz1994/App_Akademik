<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penilaian_locks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karyawan_id')->constrained('karyawan')->cascadeOnDelete();
            $table->foreignId('tahun_penilaian_id')->constrained('tahun_penilaian')->cascadeOnDelete();

            $table->boolean('is_final_submitted')->default(false);
            $table->boolean('is_locked')->default(false);

            $table->foreignId('submitted_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('submitted_at')->nullable();

            $table->foreignId('locked_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('locked_at')->nullable();

            $table->foreignId('unlocked_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('unlocked_at')->nullable();

            $table->timestamps();

            $table->unique(['karyawan_id', 'tahun_penilaian_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian_locks');
    }
};
