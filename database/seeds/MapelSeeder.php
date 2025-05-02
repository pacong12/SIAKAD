<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MapelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menghapus data yang ada sebelumnya
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('mapels')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Data mata pelajaran
        $dataMapel = [
            [
                'kode_mapel' => 'MTK',
                'created_at' => now(),
                'updated_at' => now(),
                'nama_mapel' => 'Matematika'
            ],
            [
                'kode_mapel' => 'BIN',
                'created_at' => now(),
                'updated_at' => now(),
                'nama_mapel' => 'Bahasa Indonesia'
            ],
            [
                'kode_mapel' => 'PPancasila',
                'created_at' => now(),
                'updated_at' => now(),
                'nama_mapel' => 'Pendidikan Pancasila'
            ],
            [
                'kode_mapel' => 'BING',
                'created_at' => now(),
                'updated_at' => now(),
                'nama_mapel' => 'Bahasa Inggris'
            ],
            [
                'kode_mapel' => 'IPAS',
                'created_at' => now(),
                'updated_at' => now(),
                'nama_mapel' => 'Ilmu Pengetahuan Alam'
            ],
          
            [
                'kode_mapel' => 'PAI',
                'created_at' => now(),
                'updated_at' => now(),
                'nama_mapel' => 'Pendidikan Agama Islam'
            ],
            [
                'kode_mapel' => 'BJ', 
                'created_at' => now(),
                'updated_at' => now(),
                'nama_mapel' => 'Bahasa Jawa'
            ],
            [
                'kode_mapel' => 'PJOK',
                'created_at' => now(),
                'updated_at' => now(),
                'nama_mapel' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan'
            ],
            [
                'kode_mapel' => 'SBK',
                'created_at' => now(),
                'updated_at' => now(),
                'nama_mapel' => 'Seni Budaya dan Keterampilan'
            ],
            [
                'kode_mapel' => 'P5',
                'created_at' => now(),
                'updated_at' => now(),
                'nama_mapel' => 'Proyek Penguatan Profil Pelajar Pancasila'
            ],
        ];
        
        // Tambahkan created_at dan updated_at
        foreach ($dataMapel as &$mapel) {
            $mapel['created_at'] = now();
            $mapel['updated_at'] = now();
        }
        
        // Insert data mapel
        DB::table('mapels')->insert($dataMapel);
        
        echo "Data mata pelajaran berhasil diimpor.\n";
    }
}
