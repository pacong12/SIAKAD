<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruSeeder extends Seeder
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
        DB::table('gurus')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        // Fungsi untuk mendapatkan atau membuat user
        $getUserOrCreate = function($userData) {
            $user = DB::table('users')
                ->where('username', $userData['username'])
                ->first();
                
            if (!$user) {
                return DB::table('users')->insertGetId($userData);
            }
            
            return $user->id;
        };
        
        // Data guru dari tabel SD NEGERI LIMBANGAN 06
        $guruData = [
            [
                'nip' => '196602121988061002',
                'nama' => 'IMAM SUHADI, S.Pd.SD',
                'tpt_lahir' => 'PURBALINGGA',
                'tgl_lahir' => '1966-02-12',
                'jns_kelamin' => 'L',
                'agama' => 'ISLAM',
                'alamat' => 'PURBALINGGA'
            ],
            [
                'nip' => '196503021988062002',
                'nama' => 'SUMSIYAH, S.Pd.SD.',
                'tpt_lahir' => 'CILACAP',
                'tgl_lahir' => '1965-03-02',
                'jns_kelamin' => 'P',
                'agama' => 'ISLAM',
                'alamat' => 'CILACAP'
            ],
            [
                'nip' => '196707201994012001',
                'nama' => 'WIDIASTUTI INEKE, S.Pd.SD',
                'tpt_lahir' => 'BOYOLALI',
                'tgl_lahir' => '1967-07-20',
                'jns_kelamin' => 'P',
                'agama' => 'ISLAM',
                'alamat' => 'BOYOLALI'
            ],
            [
                'nip' => '197204161999031006',
                'nama' => 'ENDRO SUMARYANTO, S.Pd.',
                'tpt_lahir' => 'MAGELANG',
                'tgl_lahir' => '1972-04-16',
                'jns_kelamin' => 'L',
                'agama' => 'ISLAM',
                'alamat' => 'MAGELANG'
            ],
            [
                'nip' => '198105052022211014',
                'nama' => 'KEMAL MUSTOFA, S.Pd.SD.',
                'tpt_lahir' => 'CILACAP',
                'tgl_lahir' => '1981-05-05',
                'jns_kelamin' => 'L',
                'agama' => 'ISLAM',
                'alamat' => 'CILACAP'
            ],
            [
                'nip' => '199103032022211005',
                'nama' => 'PUPUT SETIAWAN, S.Pd',
                'tpt_lahir' => 'CILACAP',
                'tgl_lahir' => '1991-03-03',
                'jns_kelamin' => 'L',
                'agama' => 'ISLAM',
                'alamat' => 'CILACAP'
            ],
            [
                'nip' => '198904242022211006',
                'nama' => 'AHMAD SYAEHU UMAM M,S.H.I',
                'tpt_lahir' => 'CILACAP',
                'tgl_lahir' => '1989-04-24',
                'jns_kelamin' => 'L',
                'agama' => 'ISLAM',
                'alamat' => 'CILACAP'
            ],
            [
                'nip' => '198202122022212035',
                'nama' => 'IPUNG PURWANTI,S.Pd',
                'tpt_lahir' => 'CILACAP',
                'tgl_lahir' => '1982-02-12',
                'jns_kelamin' => 'P',
                'agama' => 'ISLAM',
                'alamat' => 'CILACAP'
            ],
            [
                'nip' => '198401282022212023',
                'nama' => 'ANDRIANI FITRIANINGSIH,S.Pd',
                'tpt_lahir' => 'CILACAP',
                'tgl_lahir' => '1984-01-28',
                'jns_kelamin' => 'P',
                'agama' => 'ISLAM',
                'alamat' => 'CILACAP'
            ],
            [
                'nip' => '0', // Default NIP untuk yang kosong
                'nama' => 'IIS AISAH,S.Pd',
                'tpt_lahir' => 'CILACAP',
                'tgl_lahir' => '1989-01-07',
                'jns_kelamin' => 'P',
                'agama' => 'ISLAM',
                'alamat' => 'CILACAP'
            ]
        ];
        
        // Insert data guru
        foreach ($guruData as $guru) {
            // Buat username dari nama (ambil bagian pertama nama)
            $namaParts = explode(' ', $guru['nama']);
            $firstName = strtolower($namaParts[0]);
            
            // Buat username yang unik
            $username = $firstName;
            if (strlen($guru['nip']) >= 4) {
                $username .= substr($guru['nip'], -4);
            } else {
                $username .= rand(1000, 9999);
            }
            
            // Hapus karakter non-alfanumerik dari username
            $username = preg_replace('/[^a-z0-9]/', '', $username);
            
            // Cek struktur tabel untuk mengetahui tipe kolom nip
            $tableInfo = DB::select("DESCRIBE gurus");
            $nipInfo = null;
            
            foreach ($tableInfo as $column) {
                if ($column->Field === 'nip') {
                    $nipInfo = $column;
                    break;
                }
            }
            
            // Periksa apakah kolom nip adalah numeric atau string
            $needNumericNip = false;
            if ($nipInfo) {
                $needNumericNip = strpos(strtolower($nipInfo->Type), 'int') !== false 
                    || strpos(strtolower($nipInfo->Type), 'decimal') !== false 
                    || strpos(strtolower($nipInfo->Type), 'float') !== false;
            }
            
            // Konversi NIP sesuai tipe kolom
            $nip = $guru['nip'];
            if ($needNumericNip) {
                $nip = preg_replace('/[^0-9]/', '', $nip);
                $nip = empty($nip) ? 0 : (int)$nip;
            }
            
            // Buat user untuk guru
            $userId = $getUserOrCreate([
                'name' => $guru['nama'],
                'username' => $username,
                'password' => Hash::make('guru123'),
                'role' => 'guru',
                'image' => 'default.jpg',
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            // Masukkan data guru
            DB::table('gurus')->insert([
                'nip' => $nip,
                'nama' => $guru['nama'],
                'tpt_lahir' => $guru['tpt_lahir'],
                'tgl_lahir' => $guru['tgl_lahir'],
                'jns_kelamin' => $guru['jns_kelamin'],
                'agama' => $guru['agama'],
                'alamat' => $guru['alamat'],
                'image' => 'default.jpg',
                'user_id' => $userId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        echo "Data 10 guru berhasil diimpor.\n";
    }
}
