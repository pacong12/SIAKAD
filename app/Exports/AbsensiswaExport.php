<?php

namespace App\Exports;

use App\Absensisiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class AbsensiswaExport implements FromCollection, WithMapping, WithHeadings, WithStyles, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Absensisiswa::with(['siswa', 'kelas'])->orderBy('tanggal', 'desc')->get();
    }

    public function map($absensiswa): array
    {
        $status = '';
        switch ($absensiswa->status) {
            case 'hadir':
                $status = 'Hadir';
                break;
            case 'sakit':
                $status = 'Sakit';
                break;
            case 'izin':
                $status = 'Izin';
                break;
            case 'alpa':
                $status = 'Alpa';
                break;
            default:
                $status = $absensiswa->status;
        }
        
        return [
            $absensiswa->siswa->nama ?? 'Data tidak tersedia',
            $absensiswa->kelas->nama_kelas ?? 'Data tidak tersedia',
            date('d/m/Y', strtotime($absensiswa->tanggal)),
            $status,
            $absensiswa->keterangan ?? '-',
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Kelas',
            'Tanggal',
            'Status',
            'Keterangan',
        ];
    }
    
    public function styles(Worksheet $sheet)
    {
        return [
            // Style header row
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E4E4E4']
                ],
            ],
            // All cells
            'A' => ['alignment' => ['horizontal' => 'left']],
            'B' => ['alignment' => ['horizontal' => 'center']],
            'C' => ['alignment' => ['horizontal' => 'center']],
            'D' => ['alignment' => ['horizontal' => 'center']],
            'E' => ['alignment' => ['horizontal' => 'left']],
        ];
    }
}
