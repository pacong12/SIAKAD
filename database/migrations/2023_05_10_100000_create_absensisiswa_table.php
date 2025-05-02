<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAbsensisiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('absensisiswa', function (Blueprint $table) {
            $table->id();
            $table->integer('siswa_id');
            $table->integer('kelas_id');
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpa'])->default('hadir');
            $table->text('keterangan')->nullable();
            $table->integer('guru_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('absensisiswa');
    }
} 