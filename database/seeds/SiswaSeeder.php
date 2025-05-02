<?php

use Illuminate\Database\Seeder;
use App\Siswa;
use App\User;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SiswaSeeder extends Seeder
{
    public function run()
    {
        echo "Mulai impor data siswa...\n";
        
        // Hapus data lama sebelum mengimpor yang baru
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('siswas')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Path file Excel
        $filePath = base_path('dataex/daftar_pd-SD NEGERI LIMBANGAN 06 KECAMATAN WANAREJA-2024-12-11 09_29_21.xlsx');
        
        if (!file_exists($filePath)) {
            echo "File Excel tidak ditemukan: " . $filePath . "\n";
            return;
        }
        
        echo "Menggunakan file: " . $filePath . "\n";
        
        // Baca data Excel
        try {
            $excel = Excel::toArray(new class {}, $filePath);
            $rows = $excel[0]; // Ambil sheet pertama
            echo "File Excel berhasil dibaca. Jumlah baris: " . count($rows) . "\n";
        } catch (\Exception $e) {
            echo "Error membaca file Excel: " . $e->getMessage() . "\n";
            return;
        }
        
        // Baris mulai data (sesuaikan dengan file Excel)
        $startRow = 10; // Mulai dari baris ke-11 (indeks 10)
        
        // Indeks kolom-kolom penting (sesuaikan dengan file Excel)
        $noCol = 0;       // Kolom A
        $namaCol = 1;     // Kolom B
        $nisnCol = 4;    // Kolom E
        $nikCol = 7;     // Kolom H
        $jkCol = 3;      // Kolom C
        $tempatLahirCol = 5; // Kolom F
        $tanggalLahirCol = 6; // Kolom G
        $agamaCol = 8;    // Kolom I
        
        // Kolom alamat (J, K, L) - akan digabungkan
        $alamatJalanCol = 9;    // Kolom J
        $alamatRtCol = 10;    // Kolom K
        $alamatRwCol = 11;    // Kolom L
        $alamatDusunCol = 12;    // Kolom M
        $alamatKelurahanCol = 13;    // Kolom N
        $alamatKecamatanCol = 14;    // Kolom O
        $alamatKodeposCol = 15;    // Kolom P

        
        $namaAyahCol = 24; // Kolom Y
        $asalSekolahCol = 56;  // Kolom BE
        
        // Fungsi mendapatkan atau membuat user
        $createUser = function($nama, $nisn) {
            $username = 'siswa_' . substr(strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $nama)), 0, 10) . substr($nisn, -4);
            
            // Cek jika username sudah ada
            $existingUser = DB::table('users')->where('username', $username)->first();
            if ($existingUser) {
                $username = substr($username, 0, 15) . rand(100, 999);
            }
            
            return DB::table('users')->insertGetId([
                'name' => $nama,
                'username' => $username,
                'password' => Hash::make('siswa123'),
                'role' => 'siswa',
                'image' => 'default.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        };
        
        // Proses data siswa
        $totalSiswa = 0;
        
        for ($i = $startRow; $i < count($rows); $i++) {
            $row = $rows[$i];
            
            // Pastikan array cukup panjang
            if (!isset($row[$namaCol])) {
                continue;
            }
            
            // Ambil nomor urut dan nama
            $noUrut = isset($row[$noCol]) ? trim((string)$row[$noCol]) : '';
            $nama = isset($row[$namaCol]) ? trim((string)$row[$namaCol]) : '';
            
            // Skip baris kosong atau hanya berisi nomor urut
            if (empty($nama) || !is_string($nama) || trim($nama) === '') {
                continue;
            }
            
            // NISN (jika kosong, gunakan 10 digit terakhir NIK atau random)
            $nisn = isset($row[$nisnCol]) ? preg_replace('/[^0-9]/', '', (string)$row[$nisnCol]) : '';
            if (empty($nisn) && isset($row[$nikCol])) {
                $nik = preg_replace('/[^0-9]/', '', (string)$row[$nikCol]);
                if (strlen($nik) >= 10) {
                    $nisn = substr($nik, -10);
                }
            }
            if (empty($nisn)) {
                $nisn = mt_rand(1000000000, 9999999999);
            }
            
            // Jenis kelamin
            $jk = 'L'; // Default laki-laki
            if (isset($row[$jkCol])) {
                $jkValue = strtoupper(trim((string)$row[$jkCol]));
                if ($jkValue === 'P' || strpos($jkValue, 'P') === 0) {
                    $jk = 'P';
                }
            }
            
            // Tempat lahir
            $tempatLahir = isset($row[$tempatLahirCol]) ? trim((string)$row[$tempatLahirCol]) : 'Tidak Diketahui';
            
            // Tanggal lahir
            $tanggalLahir = '2000-01-01'; // Default
            if (isset($row[$tanggalLahirCol])) {
                try {
                    $tglValue = $row[$tanggalLahirCol];
                    if ($tglValue instanceof \DateTime) {
                        $tanggalLahir = $tglValue->format('Y-m-d');
                    } else if (is_numeric($tglValue)) {
                        $tanggalLahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($tglValue)->format('Y-m-d');
                    } else {
                        $tanggalLahir = date('Y-m-d', strtotime(str_replace('/', '-', $tglValue)));
                    }
                } catch (\Exception $e) {
                    $tanggalLahir = '2000-01-01';
                }
            }
            
            // Agama
            $agama = 'Islam'; // Default
            if (isset($row[$agamaCol])) {
                $agamaValue = trim((string)$row[$agamaCol]);
                if (!empty($agamaValue)) {
                    $agama = $agamaValue;
                }
            }
            
            // Alamat (gabungkan kolom J, K, dan L)
            $alamatJalan = isset($row[$alamatJalanCol]) ? trim((string)$row[$alamatJalanCol]) : '';
            $alamatRt = isset($row[$alamatRtCol]) ? trim((string)$row[$alamatRtCol]) : '';
            $alamatRw = isset($row[$alamatRwCol]) ? trim((string)$row[$alamatRwCol]) : '';
            $alamatDusun = isset($row[$alamatDusunCol]) ? trim((string)$row[$alamatDusunCol]) : '';
            $alamatKelurahan = isset($row[$alamatKelurahanCol]) ? trim((string)$row[$alamatKelurahanCol]) : '';
            $alamatKecamatan = isset($row[$alamatKecamatanCol]) ? trim((string)$row[$alamatKecamatanCol]) : '';
            $alamatKodepos = isset($row[$alamatKodeposCol]) ? trim((string)$row[$alamatKodeposCol]) : '';
            
            $alamat = $alamatJalan;
            if (!empty($alamatRt)) {
                $alamat .= (!empty($alamat) ? ', ' : '') . $alamatRt;
            }
            if (!empty($alamatRw)) {
                $alamat .= (!empty($alamat) ? ', ' : '') . $alamatRw;
            }
            if (!empty($alamatDusun)) {
                $alamat .= (!empty($alamat) ? ', ' : '') . $alamatDusun;
            }
            if (!empty($alamatKelurahan)) {
                $alamat .= (!empty($alamat) ? ', ' : '') . $alamatKelurahan;
            }
            if (!empty($alamatKecamatan)) {
                $alamat .= (!empty($alamat) ? ', ' : '') . $alamatKecamatan;
            }       
            if (!empty($alamatKodepos)) {
                $alamat .= (!empty($alamat) ? ', ' : '') . $alamatKodepos;
            }
            if (empty($alamat)) {
                $alamat = 'Alamat Tidak Diketahui';
            }
            
            // Nama orang tua (dari nama ayah)
            $namaOrtu = isset($row[$namaAyahCol]) ? trim((string)$row[$namaAyahCol]) : 'Nama Orang Tua Tidak Diketahui';
            
            // Asal sekolah
            $asalSekolah = isset($row[$asalSekolahCol]) ? trim((string)$row[$asalSekolahCol]) : 'SD NEGERI LIMBANGAN 06';
            if (empty($asalSekolah)) {
                $asalSekolah = 'SD NEGERI LIMBANGAN 06';
            }
            
            try {
                // Buat data siswa
            Siswa::create([
                'nisn' => $nisn,
                'nama' => $nama,
                    'tpt_lahir' => $tempatLahir,
                    'tgl_lahir' => $tanggalLahir,
                    'jns_kelamin' => $jk,
                    'agama' => $agama,
                    'alamat' => $alamat,
                    'nama_ortu' => $namaOrtu,
                    'asal_sklh' => $asalSekolah,
                    'image' => 'default.jpg',
                    'user_id' => $createUser($nama, $nisn)
                ]);
                
                $totalSiswa++;
                echo "Berhasil menambahkan siswa ke-{$totalSiswa}: {$nama} (NISN: {$nisn})\n";
            } catch (\Exception $e) {
                echo "Error pada baris " . ($i + 1) . " ({$nama}): " . $e->getMessage() . "\n";
            }
        }
        
        echo "Selesai mengimpor data siswa. Total berhasil diimpor: {$totalSiswa}\n";
    }
} 