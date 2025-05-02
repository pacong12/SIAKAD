<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Siswa;
use App\Kelas;
use App\Thnakademik;

class SiswaKelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menghapus data yang ada sebelumnya
        DB::table('siswa_kelas')->truncate();
        
        // Mendapatkan data siswa, kelas, dan tahun akademik
        $siswas = Siswa::all();
        $kelas = Kelas::all();
        $thnakademik = Thnakademik::where('status', 'aktif')->first();
        
        // Jika tidak ada tahun akademik aktif, ambil yang pertama
        if (!$thnakademik) {
            $thnakademik = Thnakademik::first();
        }
        
        // Jika tidak ada data siswa, kelas, atau tahun akademik, hentikan proses
        if ($siswas->isEmpty() || $kelas->isEmpty() || !$thnakademik) {
            return;
        }
        
        $data = [];
        
        // Untuk setiap siswa, tentukan kelas berdasarkan tingkat
        foreach ($siswas as $siswa) {
            // Tentukan tingkat kelas untuk siswa (1-6)
            $tingkat = property_exists($siswa, 'kelas') ? $siswa->kelas : rand(1, 6);
            
            // Cari kelas dengan tingkat yang sama
            $kelasWithTingkat = $kelas->where('tingkat', $tingkat)->first();
            
            // Jika kelas ditemukan, buat relasi
            if ($kelasWithTingkat) {
                $data[] = [
                    'siswa_id' => $siswa->id,
                    'kelas_id' => $kelasWithTingkat->id,
                    'thnakademik_id' => $thnakademik->id,
                    'semester' => $thnakademik->semester,
                    'status_aktif' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        
        // Masukkan data relasi siswa-kelas
        if (!empty($data)) {
            DB::table('siswa_kelas')->insert($data);
        }
    }
}
