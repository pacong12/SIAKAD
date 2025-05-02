<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveRuangIdFromJadwalmapelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jadwalmapels', function (Blueprint $table) {
            $table->dropColumn('ruang_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jadwalmapels', function (Blueprint $table) {
            $table->string('ruang_id')->nullable()->after('mapel_id');
        });
    }
}
