<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Siswa;
use App\Guru;

class UpdateUsersImageField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Untuk user dengan role guru, ambil image dari tabel gurus
        $guruUsers = User::where('role', 'guru')->get();
        foreach ($guruUsers as $user) {
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru && $guru->image) {
                $user->image = $guru->image;
                $user->save();
            }
        }

        // Untuk user dengan role siswa, ambil image dari tabel siswas
        $siswaUsers = User::where('role', 'siswa')->get();
        foreach ($siswaUsers as $user) {
            $siswa = Siswa::where('user_id', $user->id)->first();
            if ($siswa && $siswa->image) {
                $user->image = $siswa->image;
                $user->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Tidak perlu melakukan rollback khusus karena kita hanya memperbarui data
    }
}
