<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Thnakademik;

class ThnakademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Periksa apakah ada data tahun akademik
        $existingCount = DB::table('thnakademiks')->count();
        
        // Jika sudah ada data, tidak perlu menambahkan lagi
        if ($existingCount > 0) {
            echo "Data tahun akademik sudah ada, tidak perlu menambahkan lagi.\n";
            return;
        }
        
        // Menambahkan data tahun akademik dummy
        DB::table('thnakademiks')->insert([
            [
                'tahun_akademik' => '2023/2024',
                'semester' => 'Ganjil',
                'status' => 'aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'tahun_akademik' => '2023/2024',
                'semester' => 'Genap',
                'status' => 'tidak aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'tahun_akademik' => '2022/2023',
                'semester' => 'Ganjil',
                'status' => 'tidak aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'tahun_akademik' => '2022/2023',
                'semester' => 'Genap',
                'status' => 'tidak aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'tahun_akademik' => '2021/2022',
                'semester' => 'Ganjil',
                'status' => 'tidak aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'tahun_akademik' => '2021/2022',
                'semester' => 'Genap',
                'status' => 'tidak aktif',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}
