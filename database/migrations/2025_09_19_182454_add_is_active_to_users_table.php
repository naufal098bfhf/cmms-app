<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Pastikan hanya menambahkan jika kolom belum ada
            if (!Schema::hasColumn('users', 'is_active')) {
                $table->tinyInteger('is_active')->default(1)->after('role');
            } else {
                // Jika sudah ada, ubah tanpa DBAL (lebih aman)
                $table->boolean('is_active')->default(true)->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
