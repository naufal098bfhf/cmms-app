<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tugas_tetap', function (Blueprint $table) {
            if (!Schema::hasColumn('tugas_tetap', 'hari_mingguan')) {
                $table->string('hari_mingguan')->nullable()->after('jenis_tugas');
            }

            if (!Schema::hasColumn('tugas_tetap', 'tanggal_bulanan')) {
                $table->integer('tanggal_bulanan')->nullable()->after('hari_mingguan');
            }

            if (!Schema::hasColumn('tugas_tetap', 'tanggal_tahunan')) {
                $table->date('tanggal_tahunan')->nullable()->after('tanggal_bulanan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tugas_tetap', function (Blueprint $table) {
            if (Schema::hasColumn('tugas_tetap', 'hari_mingguan')) {
                $table->dropColumn('hari_mingguan');
            }

            if (Schema::hasColumn('tugas_tetap', 'tanggal_bulanan')) {
                $table->dropColumn('tanggal_bulanan');
            }

            if (Schema::hasColumn('tugas_tetap', 'tanggal_tahunan')) {
                $table->dropColumn('tanggal_tahunan');
            }
        });
    }
};
