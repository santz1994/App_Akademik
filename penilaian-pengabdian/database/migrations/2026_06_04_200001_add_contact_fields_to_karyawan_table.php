<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->string('email')->nullable()->after('alamat');
            $table->string('no_hp', 20)->nullable()->after('email');
            $table->string('kontak_darurat')->nullable()->after('no_hp');
        });
    }

    public function down(): void
    {
        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropColumn(['email', 'no_hp', 'kontak_darurat']);
        });
    }
};
