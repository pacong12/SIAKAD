<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropAbsenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('absen');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('absen', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->date('tanggal');
            $table->time('time_in');
            $table->time('time_out')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }
}
