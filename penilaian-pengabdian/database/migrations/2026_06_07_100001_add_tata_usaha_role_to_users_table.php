<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // SQLite doesn't support ENUM or MODIFY COLUMN; skip silently
            // The role column is stored as TEXT and accepts any string value
            return;
        }

        // MySQL / MariaDB
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','user','tata_usaha') NOT NULL DEFAULT 'user'");
    }

    public function down(): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            return;
        }

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','user') NOT NULL DEFAULT 'user'");
    }
};
