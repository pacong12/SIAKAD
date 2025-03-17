<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
   
    {
        DB::table('users')->insert([
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
        ]);
    }
}
