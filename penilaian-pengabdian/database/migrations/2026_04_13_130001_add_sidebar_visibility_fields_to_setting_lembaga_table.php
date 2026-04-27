<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('setting_lembaga', function (Blueprint $table) {
            $table->boolean('sidebar_show_title')->default(true)->after('sidebar_subtitle_2');
            $table->boolean('sidebar_show_subtitle_1')->default(true)->after('sidebar_show_title');
            $table->boolean('sidebar_show_subtitle_2')->default(true)->after('sidebar_show_subtitle_1');
        });
    }

    public function down(): void
    {
        Schema::table('setting_lembaga', function (Blueprint $table) {
            $table->dropColumn([
                'sidebar_show_title',
                'sidebar_show_subtitle_1',
                'sidebar_show_subtitle_2',
            ]);
        });
    }
};
