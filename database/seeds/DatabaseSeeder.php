<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Base data
        $this->call(UsersTableSeeder::class);
        $this->call(SiswaSeeder::class);
        $this->call(MapelSeeder::class);
        $this->call(ThnakademikSeeder::class);
        $this->call(GuruSeeder::class);
        $this->call(KelasSeeder::class);
        $this->call(SiswaKelasUpdate::class);
        
        // Academic data
        $this->call(JadwalmapelSeeder::class);
        $this->call(SekolahSeeder::class);
        
        // Financial data
        $this->call(JenispemSeeder::class);
        $this->call(PembayaranSeeder::class);
        
        // Data synchronization
        $this->call(UserImageUpdateSeeder::class);
    }
}
