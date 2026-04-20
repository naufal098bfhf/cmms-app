<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tugas_tetap', function (Blueprint $table) {
            if (Schema::hasColumn('tugas_tetap', 'tgl_jadwal')) {
                $table->dropColumn('tgl_jadwal');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tugas_tetap', function (Blueprint $table) {
            $table->date('tgl_jadwal')->nullable();
        });
    }
};
