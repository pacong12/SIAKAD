<?php

namespace App\Exports;

use App\Absensisiswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class AbsensiSiswaExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $request;
    
    public function __construct($request)
    {
        $this->request = $request;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Absensisiswa::query()
                    ->with(['siswa', 'kelas'])
                    ->orderBy('tanggal', 'desc');
        
        // Filter berdasarkan kelas
        if ($this->request->has('kelas_id') && $this->request->kelas_id) {
            $query->where('kelas_id', $this->request->kelas_id);
        }
        
        // Filter berdasarkan siswa
        if ($this->request->has('siswa_id') && $this->request->siswa_id) {
            $query->where('siswa_id', $this->request->siswa_id);
        }
        
        // Filter berdasarkan tanggal
        if ($this->request->has('tanggal_mulai') && $this->request->tanggal_mulai) {
            $query->whereDate('tanggal', '>=', $this->request->tanggal_mulai);
        }
        
        if ($this->request->has('tanggal_akhir') && $this->request->tanggal_akhir) {
            $query->whereDate('tanggal', '<=', $this->request->tanggal_akhir);
        }
        
        return $query->get();
    }
    
    /**
     * @var Absensisiswa $absensi
     */
    public function map($absensi): array
    {
        return [
            $absensi->tanggal ? Carbon::parse($absensi->tanggal)->format('d-m-Y') : '-',
            $absensi->siswa->nisn ?? '-',
            $absensi->siswa->nama ?? '-',
            $absensi->kelas->nama_kelas ?? '-',
            ucfirst($absensi->status),
            $absensi->keterangan
        ];
    }
    
    public function headings(): array
    {
        return [
            'Tanggal',
            'NISN',
            'Nama Siswa',
            'Kelas',
            'Status',
            'Keterangan'
        ];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF']
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => '4B5563',
                        ],
                    ],
                ]);
                
                // Autosize kolom
                $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(30);
                $event->sheet->getDelegate()->getColumnDimension('D')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(15);
                $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(40);
                
                // Hitung jumlah data
                $lastRow = count($this->collection()) + 1;
                
                // Tambahkan judul laporan di atas
                $event->sheet->mergeCells('A1:F1');
                $event->sheet->setCellValue('A1', 'LAPORAN PRESENSI SISWA');
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setBold(true);
                $event->sheet->getDelegate()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                
                // Tambahkan header lagi setelah judul
                $event->sheet->setCellValue('A2', 'Tanggal');
                $event->sheet->setCellValue('B2', 'NISN');
                $event->sheet->setCellValue('C2', 'Nama Siswa');
                $event->sheet->setCellValue('D2', 'Kelas');
                $event->sheet->setCellValue('E2', 'Status');
                $event->sheet->setCellValue('F2', 'Keterangan');
                
                $event->sheet->getStyle('A2:F2')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => 'E5E7EB',
                        ],
                    ],
                ]);
            }
        ];
    }
} 