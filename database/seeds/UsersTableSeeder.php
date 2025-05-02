<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menghapus data yang sudah ada (hanya jika username sama)
        $usernames = ['admin', 'guru', 'siswa'];
        DB::table('users')->whereIn('username', $usernames)->delete();
        
        // Memeriksa dan menambahkan data user jika belum ada
        $users = [
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'created_at' => now(),
                'updated_at' => now(),
                'role' => 'admin',
                'image' => 'admin.jpg'
            ],
            [
                'name' => 'Guru',
                'username' => 'guru',
                'password' => Hash::make('guru123'),
                'created_at' => now(),
                'updated_at' => now(),
                'role' => 'guru',
                'image' => 'guru.jpg'
            ],
            [
                'name' => 'Siswa',
                'username' => 'siswa',
                'password' => Hash::make('siswa123'),
                'created_at' => now(),
                'updated_at' => now(),
                'role' => 'siswa',
                'image' => 'siswa.jpg'
            ],
        ];
        
        foreach ($users as $userData) {
            if (!DB::table('users')->where('username', $userData['username'])->exists()) {
                DB::table('users')->insert($userData);
            }
        }
    }
}
