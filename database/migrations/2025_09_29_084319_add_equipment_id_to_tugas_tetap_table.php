<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tugas_tetap', function (Blueprint $table) {
            if (!Schema::hasColumn('tugas_tetap', 'equipment_id')) {
                $table->unsignedBigInteger('equipment_id')->nullable()->after('mekanik_id');
                $table->foreign('equipment_id')->references('id')->on('equipment')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tugas_tetap', function (Blueprint $table) {
            if (Schema::hasColumn('tugas_tetap', 'equipment_id')) {
                $table->dropForeign(['equipment_id']);
                $table->dropColumn('equipment_id');
            }
        });
    }
};
