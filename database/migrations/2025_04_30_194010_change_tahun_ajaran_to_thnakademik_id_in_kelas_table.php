<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTahunAjaranToThnakademikIdInKelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Hapus kolom tahun_ajaran
            $table->dropColumn('tahun_ajaran');
            
            // Tambahkan kolom thnakademik_id
            $table->unsignedBigInteger('thnakademik_id')->after('guru_id')->nullable();
            
            // Tambahkan foreign key constraint
            $table->foreign('thnakademik_id')->references('id')->on('thnakademiks')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kelas', function (Blueprint $table) {
            // Hapus foreign key
            $table->dropForeign(['thnakademik_id']);
            
            // Hapus kolom thnakademik_id
            $table->dropColumn('thnakademik_id');
            
            // Tambahkan kembali kolom tahun_ajaran
            $table->string('tahun_ajaran')->nullable()->after('guru_id');
        });
    }
}
