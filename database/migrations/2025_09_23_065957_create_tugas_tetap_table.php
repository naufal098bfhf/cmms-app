<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas_tetap', function (Blueprint $table) {
            $table->id();

            // =========================
            // RELASI
            // =========================

            $table->unsignedBigInteger('mekanik_id');
            $table->foreign('mekanik_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('equipment_id');
            $table->foreign('equipment_id')
                  ->references('id')
                  ->on('equipment')
                  ->onDelete('cascade');

            // =========================
            // INFO TUGAS
            // =========================

            $table->string('pemberi_tugas');

            $table->enum('jenis_tugas', [
                'mingguan',
                'bulanan',
                'tahunan'
            ]);

            // ⬅️ KUNCI UTAMA
            $table->date('tanggal_mulai');

            // =========================
            // KONFIGURASI PENJADWALAN
            // =========================

            // Mingguan
            $table->enum('hari_mingguan', [
                'senin', 'selasa', 'rabu',
                'kamis', 'jumat', 'sabtu', 'minggu'
            ])->nullable();

            // Bulanan (1–31)
            $table->unsignedTinyInteger('tanggal_bulanan')->nullable();

            // Tahunan (YYYY-MM-DD)
            $table->date('tanggal_tahunan')->nullable();

            // =========================
            // DATA TEKNIS
            // =========================

            $table->string('equipment');
            $table->string('tag_number');
            $table->string('eq_class');
            $table->string('bom')->nullable();
            $table->text('task_list');
            $table->string('lokasi');

            // =========================
            // STATUS & VALIDASI
            // =========================

            $table->enum('status', [
                'aktif',       // diproses scheduler
                'nonaktif',    // tidak dikirim
                'selesai'
            ])->default('aktif');

            $table->boolean('validasi_mp')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas_tetap');
    }
};
