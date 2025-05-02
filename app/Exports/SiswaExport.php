<?php

namespace App\Exports;

use App\Siswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\DB;

class SiswaExport implements FromCollection, WithMapping, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Eager load relasi kelasAktif dan urutkan berdasarkan kelas dan nama
        return Siswa::with(['kelasAktif' => function($query) {
                $query->orderBy('nama_kelas');
            }])
            ->select('siswas.*')
            ->join('siswa_kelas', 'siswas.id', '=', 'siswa_kelas.siswa_id')
            ->join('kelas', 'siswa_kelas.kelas_id', '=', 'kelas.id')
            ->orderBy('kelas.nama_kelas')
            ->orderBy('siswas.nama')
            ->get();
    }

    public function map($siswa): array
    {
        // Mengambil nama kelas dari relasi kelasAktif
        $kelas = $siswa->kelasAktif->isNotEmpty() ? $siswa->kelasAktif->first()->nama_kelas : '-';
        
        return [
            $siswa->nisn,
            $siswa->nama,
            $siswa->tpt_lahir,
            $siswa->tgl_lahir,
            $siswa->jns_kelamin,
            $siswa->agama,
            $siswa->alamat,
            $siswa->nama_ortu,
            $kelas, // Gunakan variabel kelas yang sudah diambil dari relasi
        ];
    }

    public function headings(): array
    {
        return [
            'NISN',
            'Nama',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jenis Kelamin',
            'Agama',
            'Alamat',
            'Nama Ortu',
            'Kelas',
        ];
    }
}
