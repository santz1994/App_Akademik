<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pangkalan', function (Blueprint $table) {
            if (! Schema::hasColumn('pangkalan', 'kepala_user_id')) {
                $table->foreignId('kepala_user_id')->nullable()->after('keterangan')
                    ->constrained('users')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('pangkalan', function (Blueprint $table) {
            $table->dropForeign(['kepala_user_id']);
            $table->dropColumn('kepala_user_id');
        });
    }
};
