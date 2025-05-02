<?php

namespace App\Exports;

use App\Jadwalmapel;
use App\Guru;
use App\Sekolah;
use App\Thnakademik;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\DB;

class JadwalguruExport implements 
    FromCollection, 
    WithMapping, 
    WithHeadings, 
    WithTitle, 
    ShouldAutoSize, 
    WithStyles, 
    WithEvents
{
    protected $guruId;
    
    public function __construct($guruId = null)
    {
        $this->guruId = $guruId;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if ($this->guruId) {
            return Jadwalmapel::with(['mapel', 'guru', 'kelas'])
                    ->where('guru_id', $this->guruId)
                    ->orderBy(DB::raw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')"))
                    ->orderBy('jam_mulai')
                    ->get();
        } else {
            return Jadwalmapel::with(['mapel', 'guru', 'kelas'])
                    ->orderBy('guru_id')
                    ->orderBy(DB::raw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')"))
                    ->orderBy('jam_mulai')
                    ->get();
        }
    }

    public function map($jadwalmapel): array
    {
        $mapelNama = $jadwalmapel->mapel ? $jadwalmapel->mapel->nama_mapel : "Mapel Terhapus";
        $guruNama = $jadwalmapel->guru ? $jadwalmapel->guru->nama : "Guru Terhapus";
        $kelasNama = $jadwalmapel->kelas ? $jadwalmapel->kelas->nama_kelas : "Kelas Tidak Ditemukan";
        
        return [
            $mapelNama,
            $kelasNama,
            $jadwalmapel->hari,
            substr($jadwalmapel->jam_mulai, 0, 5),
            substr($jadwalmapel->jam_selesai, 0, 5),
        ];
    }

    public function headings(): array
    {
        return [
            'Mata Pelajaran',
            'Kelas',
            'Hari',
            'Jam Mulai',
            'Jam Selesai',
        ];
    }
    
    public function title(): string
    {
        if ($this->guruId) {
            $guru = Guru::find($this->guruId);
            return 'Jadwal Mengajar ' . ($guru ? $guru->nama : 'Guru');
        }
        return 'Jadwal Guru';
    }
    
    public function styles(Worksheet $sheet)
    {
        $tahunAkademik = Thnakademik::where('status', 'aktif')->first();
        $sekolah = Sekolah::first();
        $guru = null;
        
        if ($this->guruId) {
            $guru = Guru::find($this->guruId);
        }
        
        // Mendapatkan tahun pelajaran
        $tahunPelajaran = date('Y').'/'.((int)date('Y')+1);
        if ($tahunAkademik) {
            $tahunPelajaran = $tahunAkademik->tahun_akademik;
        }
        
        // Mendapatkan nama sekolah
        $namaSekolah = 'SEKOLAH';
        if ($sekolah) {
            $namaSekolah = strtoupper($sekolah->nama);
        }
        
        // Mendapatkan nama guru
        $namaGuru = '';
        if ($guru) {
            $namaGuru = $guru->nama;
        }
        
        // Menulis header
        $sheet->mergeCells('A1:E1');
        $sheet->mergeCells('A2:E2');
        $sheet->mergeCells('A3:E3');
        
        $sheet->setCellValue('A1', 'JADWAL MENGAJAR GURU');
        $sheet->setCellValue('A2', $namaSekolah);
        $sheet->setCellValue('A3', 'TAHUN PELAJARAN ' . $tahunPelajaran);
        
        if ($guru) {
            $sheet->mergeCells('A4:E4');
            $sheet->setCellValue('A4', 'Nama Guru: ' . $namaGuru);
        }
        
        // Style header
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:E3')->getAlignment()->setHorizontal('center');
        
        if ($guru) {
            $sheet->getStyle('A4')->getFont()->setBold(true);
        }
        
        // Style tabel
        $lastRow = $sheet->getHighestRow();
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ];
        
        $headerStart = $guru ? 5 : 4;
        $dataStart = $headerStart + 1;
        
        $sheet->getStyle('A'.$headerStart.':E'.$lastRow)->applyFromArray($styleArray);
        $sheet->getStyle('A'.$headerStart.':E'.$headerStart)->getFont()->setBold(true);
        $sheet->getStyle('A'.$headerStart.':E'.$headerStart)->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A'.$headerStart.':E'.$headerStart)->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('DDDDDD');
        
        // Set cell alignment for data cells
        $sheet->getStyle('A'.$dataStart.':E'.$lastRow)->getAlignment()
            ->setHorizontal('center')
            ->setVertical('center');
        
        return [];
    }
    
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $lastRow = $event->sheet->getHighestRow() + 2;
                $event->sheet->getDelegate()->getRowDimension(1)->setRowHeight(20);
                $event->sheet->getDelegate()->getStyle('A1')->getFont()->setSize(14);
                
                // Tambahkan tanda tangan
                $sekolah = Sekolah::first();
                
                // Mengambil lokasi dari alamat sekolah
                $lokasi = 'Limbangan';
                if ($sekolah && $sekolah->alamat) {
                    $alamatParts = explode(',', $sekolah->alamat);
                    $lokasi = trim($alamatParts[0]);
                }
                
                $event->sheet->setCellValue('D'.$lastRow, $lokasi . ', ' . date('d F Y'));
                $event->sheet->setCellValue('D'.($lastRow+1), 'Mengetahui');
                $event->sheet->setCellValue('D'.($lastRow+2), 'Kepala Sekolah');
                $event->sheet->setCellValue('D'.($lastRow+6), $sekolah ? $sekolah->kepala_sklh : '_____________________');
                $event->sheet->setCellValue('D'.($lastRow+7), 'NIP: ');
                
                $event->sheet->getStyle('D'.$lastRow.':D'.($lastRow+7))->getAlignment()
                    ->setHorizontal('center');
            },
        ];
    }
}
