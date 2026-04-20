<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ubah kolom role agar mendukung semua nilai baru
            $table->string('role', 50)->change();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // kembalikan ke enum semula jika perlu
            $table->enum('role', ['admin', 'user', 'mekanik'])->change();
        });
    }
};
