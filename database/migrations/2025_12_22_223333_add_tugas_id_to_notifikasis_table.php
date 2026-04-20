<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('notifikasis', function (Blueprint $table) {
        $table->unsignedBigInteger('tugas_id')->nullable()->after('link');
    });
}

public function down()
{
    Schema::table('notifikasis', function (Blueprint $table) {
        $table->dropColumn('tugas_id');
    });
}

};
