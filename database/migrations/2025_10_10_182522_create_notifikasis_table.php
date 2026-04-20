<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->string('user_role'); // role tujuan notifikasi
            $table->string('pesan'); // isi notifikasi
            $table->string('link')->nullable(); // link jika ada
            $table->boolean('read')->default(false); // sudah dibaca
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('notifikasi');
    }
};

