<?php

namespace App\Exports;

use App\Jadwalmapel;
use App\Sekolah;
use App\Thnakademik;
use App\Kelas;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class JadwalmapelExport implements 
    FromCollection, 
    WithMapping, 
    WithHeadings, 
    WithTitle, 
    ShouldAutoSize, 
    WithStyles, 
    WithEvents,
    WithCustomStartCell
{
    protected $kelasId;
    protected $mapByDay = false;
    protected $headerHeight = 4;
    
    public function __construct($kelasId = null, $mapByDay = false)
    {
        $this->kelasId = $kelasId;
        $this->mapByDay = $mapByDay;
    }
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if ($this->kelasId) {
            // Jika kelas tertentu
            return Jadwalmapel::with(['mapel', 'guru', 'kelas'])
                    ->where('kelas_id', $this->kelasId)
                    ->orderBy(DB::raw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')"))
                    ->orderBy('jam_mulai')
                    ->get();
        } else {
            // Semua kelas
            return Jadwalmapel::with(['mapel', 'guru', 'kelas'])
                    ->orderBy('kelas_id')
                    ->orderBy(DB::raw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')"))
                    ->orderBy('jam_mulai')
                    ->get();
        }
    }

    public function map($jadwalmapel): array
    {
        $guruNama = "Guru Terhapus";
        if ($jadwalmapel->guru) {
            $guruNama = $jadwalmapel->guru->nama;
        }

        $mapelNama = "Mapel Terhapus";
        if ($jadwalmapel->mapel) {
            $mapelNama = $jadwalmapel->mapel->nama_mapel;
        }
        
        $kelasNama = "Kelas Tidak Ditemukan";
        if ($jadwalmapel->kelas) {
            $kelasNama = $jadwalmapel->kelas->nama_kelas;
        }
        
        return [
            $kelasNama,
            $jadwalmapel->hari,
            substr($jadwalmapel->jam_mulai, 0, 5) . ' - ' . substr($jadwalmapel->jam_selesai, 0, 5),
            $mapelNama,
            $guruNama,
        ];
    }

    public function headings(): array
    {
        return [
            'Kelas',
            'Hari',
            'Jam',
            'Mata Pelajaran',
            'Guru',
        ];
    }
    
    public function title(): string
    {
        if ($this->kelasId) {
            $kelas = Kelas::find($this->kelasId);
            return 'Jadwal ' . ($kelas ? $kelas->nama_kelas : 'Kelas');
        }
        return 'Jadwal Mata Pelajaran';
    }
    
    public function styles(Worksheet $sheet)
    {
        // Get data from the database
        $tahunAkademik = Thnakademik::where('status', 'aktif')->first();
        $sekolah = Sekolah::first();
        
        // Tahun Pelajaran
        $tahunPelajaran = $tahunAkademik ? $tahunAkademik->tahun_akademik : date('Y').'/'.((int)date('Y')+1);
        
        // Nama Sekolah
        $namaSekolah = $sekolah ? strtoupper($sekolah->nama) : 'SEKOLAH DASAR NEGERI LIMBANGAN 06';
        
        // Set header content
        $sheet->setCellValue('A1', 'JADWAL PELAJARAN');
        $sheet->setCellValue('A2', $namaSekolah);
        $sheet->setCellValue('A3', 'TAHUN PELAJARAN ' . $tahunPelajaran);
        
        // Header Cell Merging
        $sheet->mergeCells('A1:I1');
        $sheet->mergeCells('A2:I2');
        $sheet->mergeCells('A3:I3');
        
        // Header Styling
        $sheet->getStyle('A1:A3')->getFont()->setBold(true);
        $sheet->getStyle('A1:I3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1')->getFont()->setSize(14);
        
        // Create the table header for the schedule
        $sheet->setCellValue('A5', 'Kls');
        $sheet->setCellValue('B5', 'Jam Ke');
        $sheet->setCellValue('C5', 'WAKTU');
        $sheet->setCellValue('D5', 'HARI');
        $sheet->mergeCells('D5:I5');
        
        $sheet->setCellValue('D6', 'Senin');
        $sheet->setCellValue('E6', 'Selasa');
        $sheet->setCellValue('F6', 'Rabu');
        $sheet->setCellValue('G6', 'Kamis');
        $sheet->setCellValue('H6', 'Jumat');
        $sheet->setCellValue('I6', 'Sabtu');
        
        // Style the table header
        $sheet->getStyle('A5:I6')->getFont()->setBold(true);
        $sheet->getStyle('A5:I6')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A5:I6')->getAlignment()->setVertical('center');
        $sheet->getStyle('A5:I6')->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()->setARGB('D9D9D9');
        
        // Populate data
        $this->populateScheduleData($sheet);
        
        // Get last row after populating data
        $lastRow = $sheet->getHighestRow();
        
        // Set border for the whole table
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
                'outline' => [
                    'borderStyle' => Border::BORDER_MEDIUM,
                ]
            ]
        ];
        
        $sheet->getStyle('A5:I'.$lastRow)->applyFromArray($borderStyle);
        
        // Set row heights
        $sheet->getRowDimension(1)->setRowHeight(22);
        $sheet->getRowDimension(2)->setRowHeight(22);
        $sheet->getRowDimension(3)->setRowHeight(22);
        
        // Column widths
        $sheet->getColumnDimension('A')->setWidth(8);
        $sheet->getColumnDimension('B')->setWidth(10);
        $sheet->getColumnDimension('C')->setWidth(15);
        foreach (range('D', 'I') as $col) {
            $sheet->getColumnDimension($col)->setWidth(20);
        }
        
        // Add footer with signature
        $this->addSignature($sheet, $lastRow, $sekolah);
        
        return [];
    }
    
    /**
     * Populate schedule data
     */
    private function populateScheduleData(Worksheet $sheet)
    {
        // Get data from database
        if ($this->kelasId) {
            // Untuk kelas tertentu
            $jadwalQuery = Jadwalmapel::with(['mapel', 'guru', 'kelas'])
                ->where('kelas_id', $this->kelasId)
                ->orderBy(DB::raw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')"))
                ->orderBy('jam_mulai')
                ->get();
            
            $kelasCollection = Kelas::where('id', $this->kelasId)->get();
        } else {
            // Untuk semua kelas
            $jadwalQuery = Jadwalmapel::with(['mapel', 'guru', 'kelas'])
                ->orderBy('kelas_id')
                ->orderBy(DB::raw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')"))
                ->orderBy('jam_mulai')
                ->get();
            
            $kelasCollection = Kelas::orderBy('nama_kelas')->get();
        }
        
        // Buat struktur data untuk jadwal
        $jadwalData = [];
        foreach ($jadwalQuery as $jadwal) {
            $kelasId = $jadwal->kelas_id;
            $hari = $jadwal->hari;
            $jamMulai = $jadwal->jam_mulai;
            $jamSelesai = $jadwal->jam_selesai;
            
            // Format mapel dan guru
            $mapel = $jadwal->mapel ? $jadwal->mapel->nama_mapel : 'Mapel Terhapus';
            $guru = $jadwal->guru ? $jadwal->guru->nama : 'Guru Terhapus';
            
            // Buat kunci untuk mengelompokkan
            if (!isset($jadwalData[$kelasId])) {
                $jadwalData[$kelasId] = [];
            }
            if (!isset($jadwalData[$kelasId][$jamMulai])) {
                $jadwalData[$kelasId][$jamMulai] = [
                    'jam_mulai' => $jamMulai,
                    'jam_selesai' => $jamSelesai,
                    'hari' => []
                ];
            }
            
            // Simpan data jadwal per hari
            $jadwalData[$kelasId][$jamMulai]['hari'][$hari] = [
                'mapel' => $mapel,
                'guru' => $guru
            ];
        }
        
        // Definisi kolom untuk hari-hari
        $dayColumns = [
            'Senin' => 'D',
            'Selasa' => 'E',
            'Rabu' => 'F',
            'Kamis' => 'G',
            'Jumat' => 'H',
            'Sabtu' => 'I'
        ];
        
        // Mulai baris untuk data jadwal
        $currentRow = 7;
        
        // Loop untuk setiap kelas
        foreach ($kelasCollection as $kelas) {
            $kelasId = $kelas->id;
            
            // Jika tidak ada jadwal untuk kelas ini, lanjutkan ke kelas berikutnya
            if (!isset($jadwalData[$kelasId]) || empty($jadwalData[$kelasId])) {
                continue;
            }
            
            $jadwalKelas = $jadwalData[$kelasId];
            $startRow = $currentRow;
            $jamKeNumber = 1;
            
            // Set nama kelas di kolom A dengan rowspan
            $rowSpan = count($jadwalKelas);
            $sheet->setCellValue('A' . $currentRow, $kelas->nama_kelas);
            
            if ($rowSpan > 1) {
                $sheet->mergeCells('A' . $currentRow . ':A' . ($currentRow + $rowSpan - 1));
            }
            
            $sheet->getStyle('A' . $currentRow . ':A' . ($currentRow + $rowSpan - 1))->getAlignment()
                ->setHorizontal('center')
                ->setVertical('center');
            
            // Loop untuk setiap jam pelajaran
            foreach ($jadwalKelas as $jamMulai => $dataJam) {
                // Set jam ke- di kolom B
                $sheet->setCellValue('B' . $currentRow, $jamKeNumber);
                $sheet->getStyle('B' . $currentRow)->getAlignment()
                    ->setHorizontal('center')
                    ->setVertical('center');
                
                // Set waktu di kolom C
                $waktu = substr($dataJam['jam_mulai'], 0, 5) . ' - ' . substr($dataJam['jam_selesai'], 0, 5);
                $sheet->setCellValue('C' . $currentRow, $waktu);
                $sheet->getStyle('C' . $currentRow)->getAlignment()
                    ->setHorizontal('center')
                    ->setVertical('center');
                
                // Loop untuk setiap hari
                foreach ($dayColumns as $hari => $column) {
                    if (isset($dataJam['hari'][$hari])) {
                        $mapel = $dataJam['hari'][$hari]['mapel'];
                        $guru = $dataJam['hari'][$hari]['guru'];
                        
                        // Format teks untuk cell
                        $cellText = $mapel . "\n" . $guru;
                        
                        // Set nilai cell
                        $sheet->setCellValue($column . $currentRow, $cellText);
                        
                        // Set style untuk cell
                        $sheet->getStyle($column . $currentRow)->getAlignment()
                            ->setHorizontal('center')
                            ->setVertical('center')
                            ->setWrapText(true);
                        
                        // Set background color berdasarkan mata pelajaran
                        $bgColor = $this->getSubjectColor($mapel);
                        if ($bgColor) {
                            $sheet->getStyle($column . $currentRow)->getFill()
                                ->setFillType(Fill::FILL_SOLID)
                                ->getStartColor()->setARGB($bgColor);
                        }
                    } else {
                        // Biarkan kosong jika tidak ada jadwal
                        $sheet->setCellValue($column . $currentRow, '');
                    }
                }
                
                // Increment baris dan nomor jam pelajaran
                $currentRow++;
                $jamKeNumber++;
            }
            
            // Tambahkan baris kosong antara kelas
            $sheet->getRowDimension($currentRow)->setRowHeight(5);
            $currentRow++;
        }
        
        return $currentRow;
    }
    
    /**
     * Add signature at the bottom
     */
    private function addSignature(Worksheet $sheet, $lastRow, $sekolah)
    {
        // Signature position starts 2 rows after last data
        $signatureRow = $lastRow + 2;
        
        // Get location and kepala sekolah name
        $lokasi = 'Limbangan';
        $kepalaSekolah = 'Kepala Sekolah';
        $nipKepsek = 'NIP.';
        
        if ($sekolah && $sekolah->alamat) {
            $alamatParts = explode(',', $sekolah->alamat);
            $lokasi = trim($alamatParts[0]);
            
            if ($sekolah->kepala_sklh) {
                $kepalaSekolah = $sekolah->kepala_sklh;
            }
        }
        
        // Add date and location
        $sheet->setCellValue('H'.$signatureRow, $lokasi.', '.date('d F Y'));
        $sheet->mergeCells('H'.$signatureRow.':I'.$signatureRow);
        
        // Add Kepala Sekolah title
        $sheet->setCellValue('H'.($signatureRow+1), 'Kepala Sekolah');
        $sheet->mergeCells('H'.($signatureRow+1).':I'.($signatureRow+1));
        
        // Add space for signature
        $sheet->getRowDimension($signatureRow+2)->setRowHeight(10);
        $sheet->getRowDimension($signatureRow+3)->setRowHeight(10);
        $sheet->getRowDimension($signatureRow+4)->setRowHeight(10);
        
        // Add name
        $sheet->setCellValue('H'.($signatureRow+5), $kepalaSekolah);
        $sheet->mergeCells('H'.($signatureRow+5).':I'.($signatureRow+5));
        
        // Add NIP
        $sheet->setCellValue('H'.($signatureRow+6), $nipKepsek);
        $sheet->mergeCells('H'.($signatureRow+6).':I'.($signatureRow+6));
        
        // Style alignment
        $sheet->getStyle('H'.$signatureRow.':I'.($signatureRow+6))->getAlignment()
            ->setHorizontal('center');
    }
    
    /**
     * Get color code based on subject name
     */
    private function getSubjectColor($mapelName)
    {
        $mapelName = strtolower($mapelName);
        
        // Map subject keywords to colors
        $colorMap = [
            'agama' => 'C6EFCE',       // Light green
            'pai' => 'C6EFCE',         // Light green
            'matematika' => 'FFEB9C',  // Light yellow
            'bahasa indonesia' => 'FFFFFF', // White
            'bahasa inggris' => 'D8D8D8',   // Light gray
            'ipa' => 'B7DEE8',         // Light blue
            'ilmu pengetahuan alam' => 'B7DEE8', // Light blue
            'ips' => 'FFC7CE',         // Light red
            'ilmu pengetahuan sosial' => 'FFC7CE', // Light red
            'penjaskes' => 'FFCCFF',   // Light pink
            'pjok' => 'FFCCFF',        // Light pink
            'penjasorkes' => 'FFCCFF', // Light pink
            'olahraga' => 'FFCCFF',    // Light pink
            'teknologi' => 'CCFFFF',   // Light cyan
            'seni' => 'E4DFEC',        // Light purple
            'budaya' => 'E4DFEC'       // Light purple
        ];
        
        // Check for matching subject
        foreach ($colorMap as $keyword => $color) {
            if (strpos($mapelName, $keyword) !== false) {
                return $color;
            }
        }
        
        // Default color if no match
        return 'FFFFFF'; // White
    }

    /**
     * Register events
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Set print area to include all data
                $lastRow = $event->sheet->getHighestRow();
                $lastColumn = $event->sheet->getHighestColumn();
                
                // Set print area
                $event->sheet->getPageSetup()->setPrintArea('A1:'.$lastColumn.($lastRow+10));
                
                // Set paper size to A4
                $event->sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                
                // Set landscape orientation
                $event->sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                
                // Set to fit to page
                $event->sheet->getPageSetup()->setFitToWidth(1);
                $event->sheet->getPageSetup()->setFitToHeight(0);
                
                // Center on page
                $event->sheet->getPageSetup()->setHorizontalCentered(true);
                $event->sheet->getPageSetup()->setVerticalCentered(false);
            },
        ];
    }
    
    public function startCell(): string
    {
        return 'A' . ($this->headerHeight + 1);
    }
}
