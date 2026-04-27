<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('pangkalan_id')
                ->nullable()
                ->after('role')
                ->constrained('pangkalan')
                ->nullOnDelete();

            $table->boolean('is_kepala')->default(false)->after('pangkalan_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['pangkalan_id']);
            $table->dropColumn(['pangkalan_id', 'is_kepala']);
        });
    }
};
