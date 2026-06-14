<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('setting_lembaga', function (Blueprint $table) {
            $table->string('alamat_lembaga')->nullable()->after('nama_yayasan');
            $table->string('telepon_lembaga', 100)->nullable()->after('alamat_lembaga');
            $table->string('email_lembaga', 150)->nullable()->after('telepon_lembaga');
            $table->string('website_lembaga', 150)->nullable()->after('email_lembaga');
        });
    }

    public function down(): void
    {
        Schema::table('setting_lembaga', function (Blueprint $table) {
            $table->dropColumn([
                'alamat_lembaga',
                'telepon_lembaga',
                'email_lembaga',
                'website_lembaga',
            ]);
        });
    }
};
