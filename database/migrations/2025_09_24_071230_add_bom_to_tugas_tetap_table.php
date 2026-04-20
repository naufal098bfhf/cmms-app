<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tugas_tetap', function (Blueprint $table) {
            // Tambahkan hanya jika kolom bom belum ada
            if (!Schema::hasColumn('tugas_tetap', 'bom')) {
                $table->string('bom')->nullable()->after('eq_class');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tugas_tetap', function (Blueprint $table) {
            if (Schema::hasColumn('tugas_tetap', 'bom')) {
                $table->dropColumn('bom');
            }
        });
    }
};
