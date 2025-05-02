<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Siswa;
use App\Guru;
use Illuminate\Support\Facades\Log;

class UserImageUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->command->info('Memulai update image user dari tabel siswas dan gurus...');
        
        // Update image untuk user guru
        $guruCount = 0;
        $guruUsers = User::where('role', 'guru')->get();
        foreach ($guruUsers as $user) {
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru && $guru->image) {
                $user->image = $guru->image;
                $user->save();
                $guruCount++;
            }
        }
        
        // Update image untuk user siswa
        $siswaCount = 0;
        $siswaUsers = User::where('role', 'siswa')->get();
        foreach ($siswaUsers as $user) {
            $siswa = Siswa::where('user_id', $user->id)->first();
            if ($siswa && $siswa->image) {
                $user->image = $siswa->image;
                $user->save();
                $siswaCount++;
            }
        }
        
        $this->command->info("Update selesai! {$guruCount} image guru dan {$siswaCount} image siswa telah diperbarui.");
    }
}
