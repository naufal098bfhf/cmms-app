<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tugas_tetap', function (Blueprint $table) {
            // 🔥 Tambah kolom untuk tracking pengiriman terakhir
            $table->date('last_sent')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tugas_tetap', function (Blueprint $table) {
            // 🔥 Hapus kolom jika rollback
            $table->dropColumn('last_sent');
        });
    }
};
