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
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // auto increment id
            $table->string('user_id')->unique(); // custom ID (USR0001 dst)
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'mekanik', 'user', 'maintenance_planning'])->default('user');
            $table->string('photo')->nullable(); // foto opsional
            $table->string('department')->nullable(); // departemen opsional
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif'); // status user
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
