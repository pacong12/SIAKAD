<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Siswa;
use App\Kelas;
use App\Absensisiswa;
use PDF;
use App\Exports\AbsensiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Absensisiswa::with(['siswa', 'kelas'])->orderBy('tanggal', 'desc')->get();

        return view('pages.admin.absensiswa.index', compact('items'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function cetakAbsen()
    {
        return view('pages.admin.absensiswa.cetakAbsen');
    }

    public function cetakAbsenPertanggal(Request $request, $tglawal = null, $tglakhir = null)
    {
        // Ambil tanggal dari parameter URL atau dari request
        $tglawal = $tglawal ?? $request->tglawal;
        $tglakhir = $tglakhir ?? $request->tglakhir;
        
        // Validasi tanggal
        if (!$tglawal || !$tglakhir) {
            return redirect()->back()->with('error', 'Tanggal awal dan akhir harus diisi');
        }
        
        // Ambil data absensi dalam rentang tanggal
        $absenPertanggal = Absensisiswa::with(['siswa', 'kelas'])
                            ->whereBetween('tanggal', [$tglawal, $tglakhir])
                            ->orderBy('tanggal', 'desc')
                            ->get();

        $pdf = PDF::loadview('export.absenpertanggalsiswapdf', compact('absenPertanggal', 'tglawal', 'tglakhir'));
        return $pdf->stream('laporan-absen-' . Carbon::parse($tglawal)->format('dmY') . '-' . Carbon::parse($tglakhir)->format('dmY') . '.pdf');
    }

    public function cetakPDF()
    {
        $absen = Absensisiswa::with(['siswa', 'kelas'])->orderBy('tanggal', 'desc')->get();

        $pdf = PDF::loadview('export.absensiswapdf', compact('absen'));
        return $pdf->stream('laporan-absen-siswa-' . date('dmY') . '.pdf');
    }

    public function cetakEXCEL()
    {
        return Excel::download(new AbsensiswaExport, 'absen-siswa-' . date('dmY') . '.xlsx');
    }
}
