<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PembayaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Menghapus data yang ada sebelumnya
        DB::table('pembayarans')->truncate();
        
        // Mendapatkan data siswa
        $siswas = DB::table('siswas')->get();
        
        // Mendapatkan data jenis pembayaran
        $jenispems = DB::table('jenispems')->get();
        
        // Memastikan ada data siswa dan jenis pembayaran
        if ($siswas->isEmpty() || $jenispems->isEmpty()) {
            return;
        }
        
        $data = [];
        
        // Membuat data pembayaran dummy
        foreach ($siswas as $siswa) {
            // Setiap siswa membayar semua jenis pembayaran
            foreach ($jenispems as $jenispem) {
                // Status pembayaran acak (lunas atau belum lunas)
                $status = rand(0, 1) ? 'lunas' : 'belum lunas';
                
                // Tanggal pembayaran
                $tanggal = date('Y-m-d', strtotime('2023-07-15 +' . rand(0, 60) . ' days'));
                
                // Bukti pembayaran (jika lunas)
                $bukti_pembayaran = $status === 'lunas' ? 'bukti_' . $siswa->id . '_' . $jenispem->id . '.jpg' : null;
                
                $data[] = [
                    'jenispem_id' => $jenispem->id,
                    'nisn' => $siswa->nisn,
                    'nama' => $siswa->nama,
                    'kelas' => rand(1, 12), // Asumsi kelas 1-12
                    'tanggal' => $tanggal,
                    'jum_pemb' => $jenispem->nominal,
                    'keterangan' => $status === 'lunas' ? 'Pembayaran ' . $jenispem->jenis : 'Belum dibayar',
                    'status' => $status,
                    'bukti_pembayaran' => $bukti_pembayaran,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        
        // Masukkan data pembayaran
        DB::table('pembayarans')->insert($data);
    }
}
