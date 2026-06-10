<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // =====================================================
        // CRITICAL: transaksi table — most queried table
        // =====================================================

        // Composite index for the #1 query pattern in the entire app
        // Used in: store, lock, unlock, submitFinal, resolveLockState,
        //          buildReportData, LaporanController, Blade views
        Schema::table('transaksi', function (Blueprint $table) {
            $table->index(['karyawan_id', 'tahun_penilaian_id'], 'idx_transaksi_karyawan_tahun');
        });

        // For queries that also filter by pangkalan_id
        // Used in: store (check existing scores), LaporanController
        Schema::table('transaksi', function (Blueprint $table) {
            $table->index(['karyawan_id', 'pangkalan_id'], 'idx_transaksi_karyawan_pangkalan');
        });

        // For queries that filter by kategori_kinerja_id
        Schema::table('transaksi', function (Blueprint $table) {
            $table->index('kategori_kinerja_id', 'idx_transaksi_kategori');
        });

        // =====================================================
        // karyawan table — frequently filtered and sorted
        // =====================================================

        // is_active filter used in almost every karyawan query
        Schema::table('karyawan', function (Blueprint $table) {
            $table->index('is_active', 'idx_karyawan_active');
        });

        // nama_karyawan used for ORDER BY in every listing
        Schema::table('karyawan', function (Blueprint $table) {
            $table->index('nama_karyawan', 'idx_karyawan_nama');
        });

        // user_id used in User->karyawan() relationship
        Schema::table('karyawan', function (Blueprint $table) {
            $table->index('user_id', 'idx_karyawan_user');
        });

        // tahun_penilaian_id used in filtered queries
        Schema::table('karyawan', function (Blueprint $table) {
            $table->index('tahun_penilaian_id', 'idx_karyawan_tahun');
        });

        // =====================================================
        // users table
        // =====================================================

        // is_kepala used very frequently in middleware and controllers
        Schema::table('users', function (Blueprint $table) {
            $table->index('is_kepala', 'idx_users_kepala');
        });

        // role used in middleware checks
        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'idx_users_role');
        });

        // =====================================================
        // penilaian_locks table
        // =====================================================

        // is_locked filter used in lock/unlock queries
        Schema::table('penilaian_locks', function (Blueprint $table) {
            $table->index(['karyawan_id', 'tahun_penilaian_id', 'is_locked'], 'idx_lock_karyawan_tahun_locked');
        });

        // =====================================================
        // penilaian_unlock_requests table
        // =====================================================

        // status filter used in pending requests count
        Schema::table('penilaian_unlock_requests', function (Blueprint $table) {
            $table->index('status', 'idx_unlock_status');
        });

        // =====================================================
        // karyawan_pangkalan pivot table
        // =====================================================

        // Composite index for pivot lookups
        Schema::table('karyawan_pangkalan', function (Blueprint $table) {
            $table->index(['pangkalan_id', 'karyawan_id'], 'idx_kp_pangkalan_karyawan');
        });
    }

    public function down(): void
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropIndex('idx_transaksi_karyawan_tahun');
            $table->dropIndex('idx_transaksi_karyawan_pangkalan');
            $table->dropIndex('idx_transaksi_kategori');
        });

        Schema::table('karyawan', function (Blueprint $table) {
            $table->dropIndex('idx_karyawan_active');
            $table->dropIndex('idx_karyawan_nama');
            $table->dropIndex('idx_karyawan_user');
            $table->dropIndex('idx_karyawan_tahun');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_kepala');
            $table->dropIndex('idx_users_role');
        });

        Schema::table('penilaian_locks', function (Blueprint $table) {
            $table->dropIndex('idx_lock_karyawan_tahun_locked');
        });

        Schema::table('penilaian_unlock_requests', function (Blueprint $table) {
            $table->dropIndex('idx_unlock_status');
        });

        Schema::table('karyawan_pangkalan', function (Blueprint $table) {
            $table->dropIndex('idx_kp_pangkalan_karyawan');
        });
    }
};
