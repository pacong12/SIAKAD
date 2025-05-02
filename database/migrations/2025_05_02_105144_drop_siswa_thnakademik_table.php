<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropSiswaThnakademikTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('siswa_thnakademik');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('siswa_thnakademik', function (Blueprint $table) {
            $table->id();
            $table->integer('siswa_id');
            $table->integer('thnakademik_id');
            $table->timestamps();
        });
    }
}
