<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTugasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('tugas');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Recreate the table if needed to rollback
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('tanggal');
            $table->date('deadline');
            $table->integer('kelas');
            $table->text('deskripsi');
            $table->timestamps();
        });
    }
}
