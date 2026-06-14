<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('karyawan_pangkalan')) {
            Schema::create('karyawan_pangkalan', function (Blueprint $table) {
                $table->id();
                $table->foreignId('karyawan_id')->constrained('karyawan')->cascadeOnDelete();
                $table->foreignId('pangkalan_id')->constrained('pangkalan')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['karyawan_id', 'pangkalan_id']);
            });
        } else {
            // Table already exists, ensure unique constraint
            $hasUnique = false;
            try {
                $indexes = Schema::getConnection()->select("SHOW INDEX FROM karyawan_pangkalan WHERE Non_unique = 0 AND Key_name != 'PRIMARY'");
                foreach ($indexes as $idx) {
                    if ($idx->Column_name === 'karyawan_id') {
                        $hasUnique = true;
                        break;
                    }
                }
            } catch (Throwable $e) {
                // ignore
            }

            if (! $hasUnique) {
                Schema::table('karyawan_pangkalan', function (Blueprint $table) {
                    $table->unique(['karyawan_id', 'pangkalan_id']);
                });
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('karyawan_pangkalan');
    }
};
