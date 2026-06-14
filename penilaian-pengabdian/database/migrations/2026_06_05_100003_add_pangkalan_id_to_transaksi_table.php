<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            if (! Schema::hasColumn('transaksi', 'pangkalan_id')) {
                $table->foreignId('pangkalan_id')->nullable()->after('karyawan_id')
                    ->constrained('pangkalan')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['pangkalan_id']);
            $table->dropColumn('pangkalan_id');
        });
    }
};
