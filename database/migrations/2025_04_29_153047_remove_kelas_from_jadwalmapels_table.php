<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveKelasFromJadwalmapelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jadwalmapels', function (Blueprint $table) {
            $table->dropColumn('kelas');
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
            $table->integer('kelas')->after('guru_id');
        });
    }
}
