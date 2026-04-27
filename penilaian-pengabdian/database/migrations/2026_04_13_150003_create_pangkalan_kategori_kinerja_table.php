<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pangkalan_kategori_kinerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pangkalan_id')->constrained('pangkalan')->cascadeOnDelete();
            $table->foreignId('kategori_kinerja_id')->constrained('kategori_kinerja')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['pangkalan_id', 'kategori_kinerja_id'], 'pangkalan_kategori_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pangkalan_kategori_kinerja');
    }
};
