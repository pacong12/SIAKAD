<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveNilaiUhColumnsFromNilaiSiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nilai_siswa', function (Blueprint $table) {
            $table->dropColumn(['nilai_uh1', 'nilai_uh2']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nilai_siswa', function (Blueprint $table) {
            $table->integer('nilai_uh1')->nullable();
            $table->integer('nilai_uh2')->nullable();
        });
    }
}
