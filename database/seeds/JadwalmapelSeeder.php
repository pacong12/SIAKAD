<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JadwalmapelSeeder extends Seeder
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
        DB::table('jadwalmapels')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Mendapatkan ID guru berdasarkan nama
        $guruList = DB::table('gurus')->select('id', 'nama')->get();
        
        // Mendapatkan ID mapel berdasarkan kode
        $mapelList = DB::table('mapels')->select('id', 'kode_mapel')->get();
        
        // Mendapatkan data kelas
        $kelasList = DB::table('kelas')->select('id', 'tingkat', 'nama_kelas')->get();
        
        // Debug - cetak data kelas yang ditemukan
        echo "Daftar kelas yang ditemukan:\n";
        foreach ($kelasList as $kelas) {
            echo "ID: {$kelas->id}, Tingkat: {$kelas->tingkat}, Nama: {$kelas->nama_kelas}\n";
        }
        
        // Memastikan ada data guru, mapel, dan kelas
        if ($guruList->isEmpty() || $mapelList->isEmpty() || $kelasList->isEmpty()) {
            echo "Data guru, mapel, atau kelas belum tersedia. Harap jalankan seeder terkait terlebih dahulu.\n";
            return;
        }
        
        // Pemetaan guru ke mapel berdasarkan keahlian
        $guruMapel = [
            'IMAM SUHADI, S.Pd.SD' => ['MTK', 'IPAS'], // Kepala Sekolah
            'SUMSIYAH, S.Pd.SD.' => ['BIN', 'BJ', 'P5'], // Guru Kelas 6
            'WIDIASTUTI INEKE, S.Pd.SD' => ['PPancasila', 'BIN', 'P5'], // Guru Kelas 5
            'ENDRO SUMARYANTO, S.Pd.' => ['MTK', 'IPAS', 'P5'], // Guru Kelas 4
            'KEMAL MUSTOFA, S.Pd.SD.' => ['MTK', 'IPAS', 'P5'], // Guru Kelas 3
            'PUPUT SETIAWAN, S.Pd' => ['PJOK'], // Guru Olahraga
            'AHMAD SYAEHU UMAM M,S.H.I' => ['PAI'], // Guru Agama Islam
            'IPUNG PURWANTI,S.Pd' => ['BIN', 'BJ', 'P5'], // Guru Kelas 2
            'ANDRIANI FITRIANINGSIH,S.Pd' => ['SBK', 'BING'], // Guru Seni dan Bahasa Inggris
            'IIS AISAH,S.Pd' => ['BIN', 'PPancasila', 'P5'] // Guru Kelas 1
        ];
        
        // Mencari ID guru berdasarkan nama (menghilangkan spasi dan case-insensitive)
        $findGuruId = function($nama) use ($guruList) {
            // Menghilangkan gelar dalam nama guru
            $nama = preg_replace('/\,\s*S\..*$/i', '', $nama);
            
            foreach ($guruList as $guru) {
                // Menghilangkan gelar dalam guru di database
                $guruNama = preg_replace('/\,\s*S\..*$/i', '', $guru->nama);
                
                // Membersihkan dan membandingkan nama
                if (strtolower(str_replace(' ', '', $nama)) === strtolower(str_replace(' ', '', $guruNama))) {
                    return $guru->id;
                }
            }
            
            // Jika tidak ditemukan, ambil guru pertama sebagai default
            return $guruList->first()->id;
        };
        
        // Mencari ID mapel berdasarkan kode
        $findMapelId = function($kode) use ($mapelList) {
            foreach ($mapelList as $mapel) {
                if (strtoupper($kode) === strtoupper($mapel->kode_mapel)) {
                    return $mapel->id;
                }
            }
            
            // Jika tidak ditemukan, ambil mapel pertama sebagai default
            return $mapelList->first()->id;
        };
        
        // Mencari ID kelas berdasarkan tingkat
        $findKelasId = function($tingkat) use ($kelasList) {
            foreach ($kelasList as $kelas) {
                if ((int)$tingkat === (int)$kelas->tingkat) {
                    echo "Menemukan kelas tingkat {$tingkat}: ID {$kelas->id}, Nama {$kelas->nama_kelas}\n";
                    return $kelas->id;
                }
            }
            
            // Jika tidak ditemukan, log error
            echo "ERROR: Kelas tingkat {$tingkat} tidak ditemukan!\n";
            return null;
        };
        
        // Data jadwal berdasarkan file Excel
        $jadwalData = [];
        
        // Tingkatan kelas yang akan diproses
        $tingkatan = [1, 2, 3, 4, 5, 6];
        
        foreach ($tingkatan as $tingkat) {
            $kelas_id = $findKelasId($tingkat);
            
            if ($kelas_id) {
                echo "Membuat jadwal untuk kelas tingkat {$tingkat} (ID: {$kelas_id})\n";
                // Data untuk kelas ini
                $this->tambahJadwalKelas($tingkat, $jadwalData, $findGuruId, $findMapelId, $findKelasId, $guruMapel);
            } else {
                echo "Melewati kelas tingkat {$tingkat} karena ID tidak ditemukan\n";
            }
        }
        
        // Masukkan data jadwal jika ada
        if (!empty($jadwalData)) {
            DB::table('jadwalmapels')->insert($jadwalData);
            echo "Data jadwal pelajaran berhasil diimpor untuk " . count($jadwalData) . " slot jadwal.\n";
        } else {
            echo "Tidak ada data jadwal yang diimpor.\n";
        }
    }
    
    /**
     * Tambahkan jadwal untuk satu kelas
     */
    private function tambahJadwalKelas($tingkat, &$jadwalData, $findGuruId, $findMapelId, $findKelasId, $guruMapel)
    {
        $kelas_id = $findKelasId($tingkat);
        
        if (!$kelas_id) {
            echo "Tidak dapat menambahkan jadwal untuk kelas tingkat {$tingkat}: ID kelas tidak ditemukan\n";
            return;
        }
        
        // Jadwal Senin
        $this->tambahJadwalHari('Senin', $tingkat, $kelas_id, $jadwalData, $findGuruId, $findMapelId, $guruMapel, [
            ['07:30:00', '08:10:00', 'upacara'],
            ['08:10:00', '08:45:00', 'MTK'],
            ['08:45:00', '09:20:00', 'MTK'],
            ['09:20:00', '09:35:00', 'istirahat'],
            ['09:35:00', '10:10:00', 'BIN'],
            ['10:10:00', '10:45:00', 'BIN'],
            ['10:45:00', '11:20:00', 'PAI'],
            ['11:20:00', '11:35:00', 'istirahat'],
            ['11:35:00', '12:10:00', 'PAI']
        ]);
        
        // Jadwal Selasa
        $this->tambahJadwalHari('Selasa', $tingkat, $kelas_id, $jadwalData, $findGuruId, $findMapelId, $guruMapel, [
            ['07:30:00', '08:10:00', 'MTK'],
            ['08:10:00', '08:45:00', 'MTK'],
            ['08:45:00', '09:20:00', 'IPAS'],
            ['09:20:00', '09:35:00', 'istirahat'],
            ['09:35:00', '10:10:00', 'IPAS'],
            ['10:10:00', '10:45:00', 'PPancasila'],
            ['10:45:00', '11:20:00', 'PPancasila'],
            ['11:20:00', '11:35:00', 'istirahat'],
            ['11:35:00', '12:10:00', 'SBK']
        ]);
        
        // Jadwal Rabu
        $this->tambahJadwalHari('Rabu', $tingkat, $kelas_id, $jadwalData, $findGuruId, $findMapelId, $guruMapel, [
            ['07:30:00', '08:10:00', 'BIN'],
            ['08:10:00', '08:45:00', 'BIN'],
            ['08:45:00', '09:20:00', 'MTK'],
            ['09:20:00', '09:35:00', 'istirahat'],
            ['09:35:00', '10:10:00', 'MTK'],
            ['10:10:00', '10:45:00', 'BJ'],
            ['10:45:00', '11:20:00', 'BJ'],
            ['11:20:00', '11:35:00', 'istirahat'],
            ['11:35:00', '12:10:00', 'P5']
        ]);
        
        // Jadwal Kamis
        $this->tambahJadwalHari('Kamis', $tingkat, $kelas_id, $jadwalData, $findGuruId, $findMapelId, $guruMapel, [
            ['07:30:00', '08:10:00', 'IPAS'],
            ['08:10:00', '08:45:00', 'IPAS'],
            ['08:45:00', '09:20:00', 'BIN'],
            ['09:20:00', '09:35:00', 'istirahat'],
            ['09:35:00', '10:10:00', 'BIN'],
            ['10:10:00', '10:45:00', 'PJOK'],
            ['10:45:00', '11:20:00', 'PJOK'],
            ['11:20:00', '11:35:00', 'istirahat'],
            ['11:35:00', '12:10:00', 'SBK']
        ]);
        
        // Jadwal Jumat
        $this->tambahJadwalHari('Jumat', $tingkat, $kelas_id, $jadwalData, $findGuruId, $findMapelId, $guruMapel, [
            ['07:30:00', '08:10:00', 'BING'],
            ['08:10:00', '08:45:00', 'BING'],
            ['08:45:00', '09:20:00', 'PAI'],
            ['09:20:00', '09:35:00', 'istirahat'],
            ['09:35:00', '10:10:00', 'P5'],
            ['10:10:00', '10:45:00', 'P5']
        ]);
        
        // Jadwal Sabtu (hanya untuk kelas 1-3)
        if ($tingkat <= 3) {
            $this->tambahJadwalHari('Sabtu', $tingkat, $kelas_id, $jadwalData, $findGuruId, $findMapelId, $guruMapel, [
                ['07:30:00', '08:10:00', 'ekstrakurikuler'],
                ['08:10:00', '08:45:00', 'ekstrakurikuler'],
                ['08:45:00', '09:20:00', 'ekstrakurikuler']
            ]);
        }
    }
    
    /**
     * Tambahkan jadwal untuk satu hari
     */
    private function tambahJadwalHari($hari, $tingkat, $kelas_id, &$jadwalData, $findGuruId, $findMapelId, $guruMapel, $jadwalHari)
    {
        $wali_kelas = '';
        
        // Tentukan wali kelas berdasarkan tingkat
        switch ($tingkat) {
            case 1:
                $wali_kelas = 'IIS AISAH,S.Pd';
                break;
            case 2:
                $wali_kelas = 'IPUNG PURWANTI,S.Pd';
                break;
            case 3:
                $wali_kelas = 'KEMAL MUSTOFA, S.Pd.SD.';
                break;
            case 4:
                $wali_kelas = 'ENDRO SUMARYANTO, S.Pd.';
                break;
            case 5:
                $wali_kelas = 'WIDIASTUTI INEKE, S.Pd.SD';
                break;
            case 6:
                $wali_kelas = 'SUMSIYAH, S.Pd.SD.';
                break;
        }
        
        foreach ($jadwalHari as $jam) {
            $jam_mulai = $jam[0];
            $jam_selesai = $jam[1];
            $mapel_kode = strtoupper($jam[2]);
            
            // Lewati jam istirahat atau upacara
            if ($mapel_kode == 'ISTIRAHAT' || $mapel_kode == 'UPACARA' || $mapel_kode == 'EKSTRAKURIKULER') {
                continue;
            }
            
            // Tentukan guru berdasarkan mapel
            $guru_id = null;
            $mapel_id = $findMapelId($mapel_kode);
            
            // Cari guru yang mengajar mapel ini
            foreach ($guruMapel as $nama => $mapels) {
                if (in_array($mapel_kode, $mapels)) {
                    // Jika ini kelas dengan wali kelas, prioritaskan wali kelas untuk mapel umum
                    if ($nama == $wali_kelas && ($mapel_kode == 'MTK' || $mapel_kode == 'BIN' || $mapel_kode == 'IPAS' || $mapel_kode == 'PPANCASILA' || $mapel_kode == 'BJ' || $mapel_kode == 'P5')) {
                        $guru_id = $findGuruId($nama);
                        break;
                    } else if ($mapel_kode == 'PAI') {
                        // Guru agama mengajar PAI di semua kelas
                        $guru_id = $findGuruId('AHMAD SYAEHU UMAM');
                        break;
                    } else if ($mapel_kode == 'PJOK') {
                        // Guru olahraga mengajar PJOK di semua kelas
                        $guru_id = $findGuruId('PUPUT SETIAWAN');
                        break;
                    } else if ($mapel_kode == 'SBK' || $mapel_kode == 'BING') {
                        // Guru seni dan bahasa Inggris
                        $guru_id = $findGuruId('ANDRIANI FITRIANINGSIH');
                        break;
                    }
                    
                    // Jika belum ada yang cocok, gunakan guru pertama yang bisa mengajar mapel ini
                    if (!$guru_id) {
                        $guru_id = $findGuruId($nama);
                        break;
                    }
                }
            }
            
            // Jika masih belum ada guru, gunakan wali kelas
            if (!$guru_id) {
                $guru_id = $findGuruId($wali_kelas);
            }
            
            $jadwalData[] = [
                        'guru_id' => $guru_id,
                        'mapel_id' => $mapel_id,
                'kelas_id' => $kelas_id,
                'hari' => $hari,
                'jam_mulai' => $jam_mulai,
                'jam_selesai' => $jam_selesai,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
    }
}
