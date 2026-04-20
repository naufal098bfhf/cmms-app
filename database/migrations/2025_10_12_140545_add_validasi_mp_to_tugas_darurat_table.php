<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tugas_darurat', function (Blueprint $table) {
            $table->boolean('validasi_mp')->default(false)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('tugas_darurat', function (Blueprint $table) {
            $table->dropColumn('validasi_mp');
        });
    }
};
