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
        Schema::create('tugas_darurat', function (Blueprint $table) {
            $table->id();

            /* =====================
             | RELASI
             ===================== */
            $table->unsignedBigInteger('mekanik_id');
            $table->unsignedBigInteger('equipment_id')->nullable();

            /* =====================
             | INFO TUGAS
             ===================== */
            $table->string('pemberi_tugas');
            $table->dateTime('tgl_mulai');      // PENENTU KAPAN TUGAS TERKIRIM
            $table->dateTime('tgl_selesai')->nullable();

            /* =====================
             | SNAPSHOT DATA
             ===================== */
            $table->string('nama_mekanik');
            $table->string('equipment');
            $table->string('tag_number')->nullable();
            $table->string('eq_class')->nullable();
            $table->string('bom')->nullable();

            /* =====================
             | PEKERJAAN
             ===================== */
            $table->text('task_list');
            $table->string('lokasi');

            /* =====================
             | STATUS
             ===================== */
            $table->enum('status', [
                'terjadwal',
                'dikirim',
                'selesai'
            ])->default('terjadwal');

            $table->timestamps();

            /* =====================
             | FOREIGN KEY
             ===================== */
            $table->foreign('mekanik_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('equipment_id')
                  ->references('id')
                  ->on('equipment')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_darurat');
    }
};
