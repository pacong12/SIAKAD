<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Guru;
use App\Thnakademik;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menghapus data yang ada sebelumnya
        DB::table('kelas')->truncate();
        
        // Mendapatkan beberapa ID guru untuk dijadikan wali kelas
        $guru_ids = Guru::pluck('id')->toArray();
        
        // Mendapatkan tahun akademik aktif
        $thnakademik = Thnakademik::where('status', 'aktif')->first();
        
        // Jika tidak ada tahun akademik aktif, gunakan yang pertama
        if (!$thnakademik) {
            $thnakademik = Thnakademik::first();
        }
        
        // Jika tidak ada tahun akademik, keluar dari seeder
        if (!$thnakademik) {
            echo "Tidak ada tahun akademik di database. Harap jalankan ThnakademikSeeder terlebih dahulu.\n";
            return;
        }
        
        // Daftar tingkatan
        $tingkatan = [1, 2, 3, 4, 5, 6];
        
        $data = [];
        
        // Membuat data kelas dummy
        foreach ($tingkatan as $tingkat) {
            // Pilih guru secara acak untuk wali kelas
            $guru_id = !empty($guru_ids) ? $guru_ids[array_rand($guru_ids)] : null;
            $wali_kelas = null;
            
            if ($guru_id) {
                $guru = Guru::find($guru_id);
                if ($guru) {
                    $wali_kelas = $guru->nama;
                }
            }
            
            $data[] = [
                'nama_kelas' => 'Kelas ' . $tingkat,
                'tingkat' => $tingkat,
                'wali_kelas' => $wali_kelas,
                'guru_id' => $guru_id,
                'thnakademik_id' => $thnakademik->id,
                'deskripsi' => 'Kelas ' . $tingkat,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        // Masukkan data kelas
        DB::table('kelas')->insert($data);
    }
}
