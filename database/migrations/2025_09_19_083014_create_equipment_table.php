<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Equipment
            $table->integer('tag_number')->unique();
            $table->date('tanggal_masuk_aset');
            $table->string('kondisi')->default('Baik'); // opsional
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('equipment');
    }
};
