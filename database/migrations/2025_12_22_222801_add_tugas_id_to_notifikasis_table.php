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
        Schema::table('notifikasis', function (Blueprint $table) {
            // Menambahkan kolom tugas_id sebagai foreign key opsional
            $table->unsignedBigInteger('tugas_id')->nullable()->after('user_id');

            // Jika ingin membuat foreign key ke tabel tugas_darurats atau tugas_tetaps, misal:
            // $table->foreign('tugas_id')->references('id')->on('tugas_darurats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notifikasis', function (Blueprint $table) {
            // Hapus foreign key jika ada
            // $table->dropForeign(['tugas_id']);

            // Hapus kolom tugas_id
            $table->dropColumn('tugas_id');
        });
    }
};
