<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RecreateAbsensisiswaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop tabel jika sudah ada
        Schema::dropIfExists('absensisiswa');

        // Buat tabel baru
        Schema::create('absensisiswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('kelas_id');
            $table->date('tanggal');
            $table->enum('status', ['hadir', 'sakit', 'izin', 'alpa'])->default('hadir');
            $table->text('keterangan')->nullable();
            $table->unsignedBigInteger('guru_id');
            $table->timestamps();

            // Tambahkan foreign key
            $table->foreign('siswa_id')->references('id')->on('siswas');
            $table->foreign('kelas_id')->references('id')->on('kelas');
            $table->foreign('guru_id')->references('id')->on('gurus');
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