<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveStatusFromJenispemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jenispems', function (Blueprint $table) {
            $table->dropColumn('untuk_semua_kelas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jenispems', function (Blueprint $table) {
            $table->boolean('untuk_semua_kelas')->default(false);
        });
    }
}
