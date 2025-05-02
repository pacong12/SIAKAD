<?php

namespace App\Http\Controllers\Admin;

use App\Exports\JadwalmapelExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JadwalmapelRequest;
use App\Jadwalmapel;
use App\Mapel;
use App\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Sekolah;
use App\Thnakademik;
use App\Exports\JadwalguruExport;

class JadwalmapelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Jadwalmapel::with([
            'mapel', 'guru', 'kelas'
        ])->get();
        
        return view('pages.admin.jadwalmapel.index', [
            'items' => $items
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $gurus = Guru::all();
        $mapels = Mapel::all();
        $kelas = \App\Kelas::all();
        return view('pages.admin.jadwalmapel.create', [
            'guru' => $gurus,
            'mapel' => $mapels,
            'kelas' => $kelas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        // dd($data);
        Jadwalmapel::create($data);        

        return redirect('/admin/jadwalmapel')->with('status', 'Data Berhasil Dimasukan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Jadwalmapel::findOrFail($id);
        $gurus = Guru::all();
        $mapels = Mapel::all();
        $kelas = \App\Kelas::all();

        return view('pages.admin.jadwalmapel.edit', [
            'item' => $item,
            'gurus' => $gurus,
            'mapels' => $mapels,
            'kelas' => $kelas
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        
        $item = Jadwalmapel::findOrFail($id);
        $item->update($data);

        return redirect('/admin/jadwalmapel')->with('status', 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Jadwalmapel::findOrFail($id);
        $item->delete();

        return redirect('/admin/jadwalmapel')->with('status', 'Data Berhasil Dihapus');
    } 

    public function jadwal()
    {
        $items = Jadwalmapel::with([
            'mapel', 'guru', 'kelas'
        ])->get();
        
        return view('pages.admin.guru.jadwal', [
            'items' => $items
        ]);
    }

    public function exportExcel() 
    {
        return Excel::download(new JadwalmapelExport, 'Jadwalmapel.xlsx');
    }

    public function exportExcelPerKelas($id)
    {
        $kelas = \App\Kelas::findOrFail($id);
        return Excel::download(new JadwalmapelExport($id), 'Jadwalmapel-'.$kelas->nama_kelas.'.xlsx');
    }

    public function exportPdf()
    {
        $jadwal = Jadwalmapel::with(['mapel', 'guru', 'kelas'])
                ->orderBy('kelas_id')
                ->orderBy(DB::raw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')"))
                ->orderBy('jam_mulai')
                ->get();
        
        $tahunAkademik = Thnakademik::where('status', 'aktif')->first();
        $sekolah = Sekolah::first();
        
        $pdf = PDF::loadView('export.jadwalmapel', [
            'jadwal' => $jadwal,
            'tahunAkademik' => $tahunAkademik,
            'sekolah' => $sekolah
        ]);
        
        // Set ukuran halaman dan orientasi landscape
        $pdf->setPaper('a4', 'landscape');
        
        // Atur konfigurasi tambahan untuk DomPDF
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'sans-serif',
            'isRemoteEnabled' => true,
            'dpi' => 150
        ]);
        
        return $pdf->download('Jadwalmapel.pdf');
    }
    
    public function exportPdfPerKelas($id)
    {
        $kelas = \App\Kelas::findOrFail($id);
        $jadwal = Jadwalmapel::with(['mapel', 'guru', 'kelas'])
                ->where('kelas_id', $id)
                ->orderBy(DB::raw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')"))
                ->orderBy('jam_mulai')
                ->get();
        
        $tahunAkademik = Thnakademik::where('status', 'aktif')->first();
        $sekolah = Sekolah::first();
        
        $pdf = PDF::loadView('export.jadwalmapel-perkelas', [
            'jadwal' => $jadwal,
            'kelas' => $kelas,
            'tahunAkademik' => $tahunAkademik,
            'sekolah' => $sekolah
        ]);
        
        // Set ukuran halaman dan orientasi landscape
        $pdf->setPaper('a4', 'landscape');
        
        // Atur konfigurasi tambahan untuk DomPDF
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'sans-serif',
            'isRemoteEnabled' => true,
            'dpi' => 150
        ]);
        
        return $pdf->download('Jadwalmapel-'.$kelas->nama_kelas.'.pdf');
    }

    public function exportExcelGuru($id) 
    {
        $guru = Guru::findOrFail($id);
        return Excel::download(new JadwalguruExport($id), 'Jadwal-'.$guru->nama.'.xlsx');
    }

    public function exportPdfGuru($id)
    {
        $guru = Guru::findOrFail($id);
        $jadwal = Jadwalmapel::with(['mapel', 'guru', 'kelas'])
                ->where('guru_id', $id)
                ->orderBy(DB::raw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu')"))
                ->orderBy('jam_mulai')
                ->get();
        
        $tahunAkademik = Thnakademik::where('status', 'aktif')->first();
        $sekolah = Sekolah::first();
        
        $pdf = PDF::loadView('export.jadwalgurupdf', [
            'jadwal' => $jadwal,
            'guru' => $guru,
            'tahunAkademik' => $tahunAkademik,
            'sekolah' => $sekolah
        ]);
        
        // Set ukuran halaman dan orientasi landscape
        $pdf->setPaper('a4', 'landscape');
        
        // Atur konfigurasi tambahan untuk DomPDF
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isPhpEnabled' => true,
            'defaultFont' => 'sans-serif',
            'isRemoteEnabled' => true,
            'dpi' => 150
        ]);
        
        return $pdf->download('Jadwal-'.$guru->nama.'.pdf');
    }
}
