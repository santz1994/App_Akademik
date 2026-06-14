<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_kinerja_kompetensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_kinerja_id')->constrained('kategori_kinerja')->cascadeOnDelete();
            $table->foreignId('kompetensi_id')->constrained('kompetensi')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['kategori_kinerja_id', 'kompetensi_id'], 'uk_kategori_kompetensi');
        });

        if (Schema::hasTable('kompetensi') && Schema::hasColumn('kompetensi', 'kategori_kinerja_id')) {
            $now = now();

            DB::table('kompetensi')
                ->whereNotNull('kategori_kinerja_id')
                ->select('id', 'kategori_kinerja_id')
                ->orderBy('id')
                ->chunkById(200, function ($rows) use ($now) {
                    $payload = [];

                    foreach ($rows as $row) {
                        $payload[] = [
                            'kategori_kinerja_id' => $row->kategori_kinerja_id,
                            'kompetensi_id' => $row->id,
                            'created_at' => $now,
                            'updated_at' => $now,
                        ];
                    }

                    if (! empty($payload)) {
                        DB::table('kategori_kinerja_kompetensi')->insertOrIgnore($payload);
                    }
                });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_kinerja_kompetensi');
    }
};
