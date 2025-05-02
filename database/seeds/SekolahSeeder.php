<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menghapus data yang ada sebelumnya
        DB::table('sekolahs')->truncate();
        
        // Memasukkan data profil sekolah
        DB::table('sekolahs')->insert([
            [
                'nama' => 'SD Negeri Limbangan 06',
                'alamat' => ' Saungluhur RT 005/010 Desa Limbangan Wanareja Cilacap',
                'email' => 'info@sdnlimbangan06.sch.id',
                'no_tlpn' => '0721-123456',
                'akreditasi' => 'B',
                'kepala_sklh' => 'Dr. Sukarno, M.Pd.',
                'image' => 'logo_sekolah.png',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
