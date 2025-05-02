<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenispemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menghapus data yang ada sebelumnya
        DB::table('jenispems')->truncate();
        
        // Menambahkan data jenis pembayaran dummy
        DB::table('jenispems')->insert([
            [
                'jenis' => 'SPP Bulanan',
                'nominal' => 250000,
                'tanggal_mulai' => '2023-07-01',
                'tanggal_selesai' => '2024-06-30',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'jenis' => 'Uang Buku',
                'nominal' => 500000,
                'tanggal_mulai' => '2023-07-01',
                'tanggal_selesai' => '2023-08-31',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'jenis' => 'Uang Seragam',
                'nominal' => 450000,
                'tanggal_mulai' => '2023-07-01',
                'tanggal_selesai' => '2023-08-31',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'jenis' => 'Iuran Komputer',
                'nominal' => 150000,
                'tanggal_mulai' => '2023-07-01',
                'tanggal_selesai' => '2024-06-30',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'jenis' => 'Iuran Perpustakaan',
                'nominal' => 100000,
                'tanggal_mulai' => '2023-07-01',
                'tanggal_selesai' => '2024-06-30',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'jenis' => 'Uang Ujian Semester',
                'nominal' => 200000,
                'tanggal_mulai' => '2023-12-01',
                'tanggal_selesai' => '2023-12-31',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'jenis' => 'Uang Kegiatan',
                'nominal' => 300000,
                'tanggal_mulai' => '2023-07-01',
                'tanggal_selesai' => '2024-06-30',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
