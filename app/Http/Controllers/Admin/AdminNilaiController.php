<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mapel;
use App\Siswa;
use App\Kelas;
use App\Thnakademik;
use App\Jadwalmapel;
use Illuminate\Http\Request;
use PDF;
use DB;

class AdminNilaiController extends Controller
{
    public function index()
    {
        // Ambil semua kelas yang tersedia
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('pages.admin.nilai.index', compact('kelas'));
    }

    public function pilihMapel($kelas_id)
    {
        // Ambil detail kelas
        $kelas = Kelas::findOrFail($kelas_id);
        
        // Ambil daftar mapel yang tersedia
        $mapels = Mapel::all();
        
        // Ambil tahun akademik yang aktif
        $tahunAktif = Thnakademik::where('status', 'aktif')->first();
        
        // Jika tidak ada tahun akademik aktif, ambil yang pertama
        if (!$tahunAktif) {
            $tahunAktif = Thnakademik::first();
        }
        
        return view('pages.admin.nilai.pilih-mapel', compact('kelas', 'mapels', 'tahunAktif'));
    }

    public function inputNilai($kelas_id, $mapel_id)
    {
        // Ambil detail kelas
        $kelas = Kelas::findOrFail($kelas_id);
        
        // Ambil detail mapel
        $mapel = Mapel::findOrFail($mapel_id);
        
        // Ambil siswa yang terdaftar di kelas tersebut
        $siswa = $kelas->siswa()->wherePivot('status_aktif', true)->get();
        
        // Ambil tahun akademik yang aktif
        $tahunAktif = Thnakademik::where('status', 'aktif')->first();
        
        // Jika tidak ada tahun akademik aktif, ambil yang pertama
        if (!$tahunAktif) {
            $tahunAktif = Thnakademik::first();
        }
        
        // Ambil nilai siswa untuk mapel yang dipilih
        foreach ($siswa as $s) {
            $nilai = DB::table('nilai_siswa')
                    ->where('siswa_id', $s->id)
                    ->where('mapel_id', $mapel_id)
                    ->where('thnakademik_id', $tahunAktif->id)
                    ->first();
            
            if ($nilai) {
                $s->nilai_uts = $nilai->uts;
                $s->nilai_uas = $nilai->uas;
                $s->nilai_status = $nilai->status;
            } else {
                $s->nilai_uts = null;
                $s->nilai_uas = null;
                $s->nilai_status = 'Lulus';
            }
        }
        
        return view('pages.admin.nilai.input', compact('kelas', 'mapel', 'siswa', 'tahunAktif'));
    }

    public function simpanNilai(Request $request)
    {
        $kelas_id = $request->kelas_id;
        $mapel_id = $request->mapel_id;
        $thnakademik_id = $request->thnakademik_id;
        $siswa_ids = $request->siswa_id;
        $uts_values = $request->uts;
        $uas_values = $request->uas;
        $status_values = $request->status;
        
        // Proses untuk setiap siswa
        for ($i = 0; $i < count($siswa_ids); $i++) {
            $siswa_id = $siswa_ids[$i];
            $uts = $uts_values[$i];
            $uas = $uas_values[$i];
            $status = $status_values[$i];
            
            // Cek apakah sudah ada nilai untuk siswa, mapel, dan tahun akademik ini
            $ada = DB::table('nilai_siswa')
                    ->where('siswa_id', $siswa_id)
                    ->where('mapel_id', $mapel_id)
                    ->where('thnakademik_id', $thnakademik_id)
                    ->exists();
            
            if ($ada) {
                // Update nilai yang sudah ada
                DB::table('nilai_siswa')
                    ->where('siswa_id', $siswa_id)
                    ->where('mapel_id', $mapel_id)
                    ->where('thnakademik_id', $thnakademik_id)
                    ->update([
                        'uts' => $uts,
                        'uas' => $uas,
                        'status' => $status,
                    ]);
            } else {
                // Tambahkan nilai baru
                DB::table('nilai_siswa')->insert([
                    'siswa_id' => $siswa_id,
                    'mapel_id' => $mapel_id,
                    'thnakademik_id' => $thnakademik_id,
                    'uts' => $uts,
                    'uas' => $uas,
                    'status' => $status,
                ]);
            }
        }
        
        return redirect()->route('admin.nilai.input', ['kelas_id' => $kelas_id, 'mapel_id' => $mapel_id])
                ->with('status', 'Nilai berhasil disimpan');
    }

    public function cetakIndex()
    {
        // Ambil semua kelas yang tersedia
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('pages.admin.nilai.cetak-index', compact('kelas'));
    }

    public function cetakSiswaKelas($kelas_id)
    {
        // Ambil detail kelas
        $kelas = Kelas::findOrFail($kelas_id);
        
        // Ambil siswa yang terdaftar di kelas tersebut
        $siswa = $kelas->siswa()->wherePivot('status_aktif', true)->get();
        
        return view('pages.admin.nilai.cetak-siswa', compact('kelas', 'siswa'));
    }

    public function cetakRapor(Request $request)
    {
        $siswa_id = $request->siswa_id;
        $thnakademik_id = $request->thnakademik_id ?: Thnakademik::where('status', 'aktif')->first()->id;
        
        // Ambil data siswa
        $siswa = Siswa::with(['mapel' => function($query) use($thnakademik_id) {
            $query->wherePivot('thnakademik_id', $thnakademik_id);
        }])->findOrFail($siswa_id);
        
        // Ambil data tahun akademik
        $tahunAkademik = Thnakademik::findOrFail($thnakademik_id);
        
        // Ambil data sekolah
        $sekolah = \App\Sekolah::first();
        
        $pdf = PDF::loadView('export.nilaisiswapdf', [
            'siswa' => $siswa,
            'tahunAkademik' => $tahunAkademik,
            'sekolah' => $sekolah
        ]);
        
        return $pdf->download('Nilai-'.$siswa->nama.'.pdf');
    }
    
    public function cetakRaporKelas($kelas_id)
    {
        // Ambil detail kelas
        $kelas = Kelas::findOrFail($kelas_id);
        
        // Ambil siswa yang terdaftar di kelas tersebut
        $siswa = $kelas->siswa()->wherePivot('status_aktif', true)->get();
        
        // Ambil tahun akademik yang aktif
        $tahunAktif = Thnakademik::where('status', 'aktif')->first();
        
        // Ambil data sekolah
        $sekolah = \App\Sekolah::first();
        
        $pdf = PDF::loadView('export.nilai-kelas-pdf', [
            'kelas' => $kelas,
            'siswa' => $siswa,
            'tahunAkademik' => $tahunAktif,
            'sekolah' => $sekolah
        ]);
        
        return $pdf->download('Nilai-Kelas-'.$kelas->nama_kelas.'.pdf');
    }
} 