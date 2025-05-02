<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PembayaranRequest;
use App\Jenispem;
use App\Pembayaran;
use PDF;
use Carbon\Carbon;
use App\Exports\PembayaranExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use App\Siswa;

class PembayaranController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $jenispems = Jenispem::all();
        $siswas = Siswa::all();
        $query = Pembayaran::with('jenispem')->orderBy('id', 'DESC');

        // Filter Kelas
        if ($request->has('kelas') && $request->kelas != '') {
            $query->where('kelas', $request->kelas);
        }

        // Filter Status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter Jenis Pembayaran
        if ($request->has('jenispem_id') && $request->jenispem_id != '') {
            $query->where('jenispem_id', $request->jenispem_id);
        }

        $items = $query->get();

        return view('pages.admin.pembayaran.index', compact('jenispems', 'items', 'siswas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $siswas = Siswa::all();
        $jenispems = Jenispem::all();
        return view('pages.admin.pembayaran.create', compact('siswas', 'jenispems'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PembayaranRequest $request)
    {
        $tanggal = Carbon::now();
        
        // Ambil data siswa berdasarkan NISN
        $siswa = Siswa::where('nisn', $request->nisn)->first();
        
        // Ambil data jenis pembayaran untuk mendapatkan nominal
        $jenispem = Jenispem::find($request->jenispem_id);

        $pemb = new Pembayaran;
        $pemb->nisn = $request->nisn;
        $pemb->nama = $siswa->nama; // Ambil nama dari data siswa
        $pemb->jenispem_id = $request->jenispem_id;
        $pemb->tanggal = $tanggal;
        $pemb->kelas = $request->kelas;
        $pemb->jum_pemb = $jenispem->nominal; // Gunakan nominal dari jenis pembayaran
        $pemb->keterangan = $request->keterangan;
        $pemb->status = 'belum lunas';

        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/bukti_pembayaran', $filename);
            $pemb->bukti_pembayaran = $filename;
        }

        $pemb->save();

        return redirect()->route('pembayaran.index')->with('status', 'Data berhasil Ditambah');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Pembayaran::findOrFail($id);

        return view('pages.admin.pembayaran.detail', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Pembayaran::findOrFail($id);
        $jenispems = Jenispem::all();

        return view('pages.admin.pembayaran.edit', compact('jenispems', 'item'));
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
        $tanggal = Carbon::now();

        $pemb = Pembayaran::find($id);

        $pemb->nisn = $request->nisn;
        $pemb->nama = $request->nama;
        $pemb->jenispem_id = $request->jenispem_id;
        $pemb->tanggal = $tanggal;
        $pemb->kelas = $request->kelas;
        $pemb->jum_pemb = $request->jum_pemb;
        $pemb->keterangan = $request->keterangan;
        
        $pemb->save();

        return redirect()->route('pembayaran.index')->with('status', 'Data berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // $item = Pembayaran::findOrFail($id);

        // $item->delete();

        // return redirect()->route('pembayaran.index')->with('status', 'Data berhasil Dihapus');
    }

    public function hapus($id)
    {
        $item = Pembayaran::findOrFail($id);

        $item->delete();

        return redirect()->route('pembayaran.index')->with('status', 'Data berhasil Dihapus');
    }

    public function cetakPDF()
    {
        $pembayaran = Pembayaran::all();
        $sekolah = \App\Sekolah::first();
        $pdf = PDF::loadview('export.pembayaranpdf', compact('pembayaran', 'sekolah'));
        return $pdf->download('laporan-pembayaran.pdf');
    }

    public function cetakEXCEL()
    {
        return Excel::download(new PembayaranExport, 'pembayaran.xlsx');
    }

    public function cetakPembayaran()
    {
        return view('pages.admin.pembayaran.cetakPembayaran');
    }

    public function cetakPembayaranPertanggal($tglawal, $tglakhir, Request $request)
    {
        $query = Pembayaran::with('jenispem')->whereBetween('tanggal', [$tglawal, $tglakhir]);
        
        // Filter berdasarkan kelas jika ada
        if ($request->has('kelas') && $request->kelas != '') {
            $query->where('kelas', $request->kelas);
        }
        
        // Filter berdasarkan status jika ada
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        $pembayaranPertanggal = $query->get();
        
        // Jika request meminta download PDF
        if ($request->has('download') && $request->download == 'pdf') {
            $sekolah = \App\Sekolah::first();
            $pdf = PDF::loadview('export.pembayaranpertanggalpdf', compact('pembayaranPertanggal', 'tglawal', 'tglakhir', 'sekolah'));
            return $pdf->download('laporan-pembayaran-' . $tglawal . '-' . $tglakhir . '.pdf');
        }
        
        return view('pages.admin.pembayaran.cetakPembayaranPertanggal', compact('pembayaranPertanggal'));
    }

    public function cetakDetail($id)
    {
        $item = Pembayaran::findOrFail($id);
        $sekolah = \App\Sekolah::first();
        $pdf = PDF::loadview('export.pembayarandetailpdf', compact('item', 'sekolah'));
        return $pdf->download('detail-pembayaran-'.$item->nisn.'.pdf');
    }

    public function updateStatus(Request $request, $id)
    {
        $pemb = Pembayaran::findOrFail($id);
        $pemb->status = $request->status;
        $pemb->save();

        return redirect()->route('pembayaran.index')->with('status', 'Status pembayaran berhasil diubah');
    }

    public function createBulkPayment(Request $request)
    {
        $tanggal = Carbon::now();
        $jenispem = Jenispem::find($request->jenispem_id);
        
        if (!$jenispem) {
            return redirect()->back()->with('error', 'Jenis pembayaran tidak ditemukan');
        }

        // Ambil siswa berdasarkan tipe pembayaran
        if ($request->has('tipe_pembayaran') && $request->tipe_pembayaran == 'perkelas') {
            if (!$request->has('kelas_pembayaran') || empty($request->kelas_pembayaran)) {
                return redirect()->back()->with('error', 'Silakan pilih kelas terlebih dahulu');
            }
            
            // Menggunakan relasi kelas dan siswa dari tabel siswa_kelas
            $siswas = Siswa::whereHas('kelasAktif', function($query) use ($request) {
                $query->where('kelas.tingkat', $request->kelas_pembayaran);
            })->get();
            
            $pesanStatus = 'Pembayaran berhasil dibuat untuk siswa kelas ' . $request->kelas_pembayaran;
        } else {
            // Ambil semua siswa untuk seluruh kelas
            $siswas = Siswa::all();
            $pesanStatus = 'Pembayaran berhasil dibuat untuk semua siswa';
        }
        
        foreach ($siswas as $siswa) {
            // Mendapatkan kelas siswa saat ini
            $kelasSiswa = $siswa->kelasAktif->first();
            $kelasNomor = $kelasSiswa ? $kelasSiswa->tingkat : null;
            
            // Cek apakah siswa sudah memiliki pembayaran ini
            $existingPayment = Pembayaran::where('nisn', $siswa->nisn)
                ->where('jenispem_id', $jenispem->id)
                ->whereYear('tanggal', $tanggal->year)
                ->first();

            if (!$existingPayment) {
                $pemb = new Pembayaran;
                $pemb->nisn = $siswa->nisn;
                $pemb->nama = $siswa->nama;
                $pemb->jenispem_id = $jenispem->id;
                $pemb->tanggal = $tanggal;
                $pemb->kelas = $kelasNomor;
                $pemb->jum_pemb = $jenispem->nominal;
                $pemb->keterangan = $request->keterangan;
                $pemb->status = 'belum lunas';
                $pemb->save();
            }
        }

        return redirect()->route('pembayaran.index')->with('status', $pesanStatus);
    }
}
