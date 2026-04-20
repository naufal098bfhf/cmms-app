<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ✅ Hapus kolom status jika ada
            if (Schema::hasColumn('users', 'status')) {
                $table->dropColumn('status');
            }

            // ✅ Ubah kolom role agar bisa menampung nilai panjang (misalnya 'maintenance_planning')
            if (Schema::hasColumn('users', 'role')) {
                $table->string('role', 50)->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // ✅ Kembalikan kolom status
            if (!Schema::hasColumn('users', 'status')) {
                $table->string('status')->nullable();
            }

            // ✅ Kembalikan panjang kolom role ke ukuran default (misal 20)
            if (Schema::hasColumn('users', 'role')) {
                $table->string('role', 20)->change();
            }
        });
    }
};
