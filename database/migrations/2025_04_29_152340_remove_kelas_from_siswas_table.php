<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveKelasFromSiswasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('siswas', function (Blueprint $table) {
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
        Schema::table('siswas', function (Blueprint $table) {
            $table->string('kelas')->nullable()->after('nama_ortu');
        });
    }
}
