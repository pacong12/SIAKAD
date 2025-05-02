<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSiswaKelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('siswa_kelas', function (Blueprint $table) {
            $table->id();
            $table->integer('siswa_id');
            $table->integer('kelas_id');
            $table->integer('thnakademik_id');
            $table->enum('semester', ['ganjil', 'genap']);
            $table->boolean('status_aktif')->default(true);
            $table->softDeletes();
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
        Schema::dropIfExists('siswa_kelas');
    }
}
