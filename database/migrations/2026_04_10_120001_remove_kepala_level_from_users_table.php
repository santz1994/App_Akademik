<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('users', 'kepala_level')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('kepala_level');
            });
        }
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'kepala_level')) {
            Schema::table('users', function (Blueprint $table) {
                $table->enum('kepala_level', ['bagian', 'departement'])
                    ->nullable()
                    ->after('is_kepala');
            });
        }
    }
};
