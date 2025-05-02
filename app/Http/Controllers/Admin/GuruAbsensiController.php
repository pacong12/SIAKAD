<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Kelas;
use App\Siswa;
use App\Absensisiswa;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiSiswaExport;

class GuruAbsensiController extends Controller
{
    /**
     * Menampilkan halaman absensi siswa
     */
    public function index(Request $request)
    {
        // Ambil semua kelas
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        
        return view('pages.admin.guru.absensi', compact('kelas'));
    }
    
    /**
     * Menampilkan form absensi untuk kelas tertentu
     */
    public function proses($id)
    {
        // Ambil data kelas
        $kelasInfo = Kelas::findOrFail($id);
        
        // Ambil data siswa dari kelas yang dipilih
        $siswa = $kelasInfo->siswa()
                    ->wherePivot('status_aktif', true)
                    ->orderBy('nama')
                    ->get();
        
        // Parameter tanggal default hari ini
        $tanggal = request('tanggal') ?? date('Y-m-d');
        
        // Inisialisasi array absensi
        $absensi = [];
        $totalAbsen = [
            'hadir' => 0,
            'sakit' => 0,
            'izin' => 0,
            'alpa' => 0
        ];
        
        // Ambil data absensi yang sudah ada
        $existingAbsensi = Absensisiswa::where('tanggal', $tanggal)
                                ->where('kelas_id', $id)
                                ->get();
        
        // Kelompokkan absensi berdasarkan siswa_id untuk memudahkan akses
        foreach ($existingAbsensi as $item) {
            $absensi[$item->siswa_id] = $item;
            
            // Hitung total per status
            if (isset($totalAbsen[$item->status])) {
                $totalAbsen[$item->status]++;
            }
        }
        
        return view('pages.admin.guru.absensi-form', compact('kelasInfo', 'siswa', 'absensi', 'totalAbsen', 'tanggal'));
    }
    
    /**
     * Menyimpan data absensi siswa
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => 'required|date',
            'siswa_id' => 'required|array',
            'status' => 'required|array',
        ]);
        
        // Mulai transaksi database
        DB::beginTransaction();
        
        try {
            $kelasId = $request->kelas_id;
            $tanggal = $request->tanggal;
            
            // Hapus absensi yang sudah ada untuk kelas dan tanggal tersebut
            Absensisiswa::where('tanggal', $tanggal)
                        ->where('kelas_id', $kelasId)
                        ->delete();
            
            // Simpan data absensi baru
            foreach ($request->siswa_id as $index => $siswaId) {
                if (isset($request->status[$siswaId])) {
                    $absensi = new Absensisiswa();
                    $absensi->siswa_id = $siswaId;
                    $absensi->kelas_id = $kelasId;
                    $absensi->tanggal = $tanggal;
                    $absensi->status = $request->status[$siswaId];
                    $absensi->keterangan = $request->keterangan[$siswaId] ?? null;
                    $absensi->guru_id = Auth::id();
                    $absensi->save();
                }
            }
            
            DB::commit();
            
            return redirect()->back()->with('status', 'Data absensi berhasil disimpan');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Menampilkan laporan absensi
     */
    public function laporan(Request $request)
    {
        // Ambil semua kelas
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        
        // Ambil daftar siswa untuk dropdown
        $siswa = Siswa::orderBy('nama')->get();
        
        // Filter laporan
        $query = Absensisiswa::query()
                    ->with(['siswa', 'kelas'])
                    ->orderBy('tanggal', 'desc');
        
        // Filter berdasarkan kelas
        if ($request->has('kelas_id') && $request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }
        
        // Filter berdasarkan siswa
        if ($request->has('siswa_id') && $request->siswa_id) {
            $query->where('siswa_id', $request->siswa_id);
        }
        
        // Filter berdasarkan tanggal
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }
        
        // Ambil data absensi
        $absensi = $query->get();
        
        // Hitung rangkuman
        $summary = [
            'hadir' => $absensi->where('status', 'hadir')->count(),
            'sakit' => $absensi->where('status', 'sakit')->count(),
            'izin' => $absensi->where('status', 'izin')->count(),
            'alpa' => $absensi->where('status', 'alpa')->count(),
        ];
        
        return view('pages.admin.guru.laporan-absensi', compact('kelas', 'siswa', 'absensi', 'summary'));
    }
    
    /**
     * Mencetak laporan absensi dalam bentuk PDF
     */
    public function cetakPdf(Request $request)
    {
        // Filter laporan
        $query = Absensisiswa::query()
                    ->with(['siswa', 'kelas'])
                    ->orderBy('tanggal', 'desc');
        
        // Filter berdasarkan kelas
        if ($request->has('kelas_id') && $request->kelas_id) {
            $query->where('kelas_id', $request->kelas_id);
        }
        
        // Filter berdasarkan siswa
        if ($request->has('siswa_id') && $request->siswa_id) {
            $query->where('siswa_id', $request->siswa_id);
        }
        
        // Filter berdasarkan tanggal
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai) {
            $query->whereDate('tanggal', '>=', $request->tanggal_mulai);
        }
        
        if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
            $query->whereDate('tanggal', '<=', $request->tanggal_akhir);
        }
        
        // Ambil data absensi
        $absensi = $query->get();
        
        // Hitung rangkuman
        $rangkuman = [
            'hadir' => $absensi->where('status', 'hadir')->count(),
            'sakit' => $absensi->where('status', 'sakit')->count(),
            'izin' => $absensi->where('status', 'izin')->count(),
            'alpa' => $absensi->where('status', 'alpa')->count(),
        ];
        
        // Siapkan judul laporan
        $judul = "Laporan Absensi Siswa";
        if ($request->has('kelas_id') && $request->kelas_id) {
            $kelas = Kelas::find($request->kelas_id);
            $judul .= " Kelas " . $kelas->nama_kelas;
        }
        
        if ($request->has('tanggal_mulai') && $request->tanggal_mulai) {
            $judul .= " Periode " . Carbon::parse($request->tanggal_mulai)->format('d-m-Y');
            
            if ($request->has('tanggal_akhir') && $request->tanggal_akhir) {
                $judul .= " s/d " . Carbon::parse($request->tanggal_akhir)->format('d-m-Y');
            }
        }
        
        $pdf = PDF::loadView('pages.admin.guru.cetak-absensi', compact('absensi', 'rangkuman', 'judul'));
        return $pdf->stream('laporan-absensi-siswa.pdf');
    }
    
    /**
     * Export data absensi ke Excel
     */
    public function exportExcel(Request $request)
    {
        return Excel::download(new AbsensiSiswaExport($request), 'laporan-absensi-siswa.xlsx');
    }
} 