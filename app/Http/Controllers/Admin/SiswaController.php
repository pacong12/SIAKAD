<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SiswaRequest;
use App\Siswa;
use App\Jadwalmapel;
use App\User;
use App\Mapel;
use App\Sekolah;
use App\Info;
use App\Thnakademik;
use App\Absensisiswa;
// use Auth;
use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use App\Exports\nilaiSiswaExport;
use App\Exports\NilaiExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Siswa::with('kelasAktif')->get();
        
        return view('pages.admin.siswa.index', [
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
        $kelas = \App\Kelas::all();
        return view('pages.admin.siswa.create', compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SiswaRequest $request)
    {
        // insert ke table users
        $user = new User;
        $user->role = 'siswa';
        $user->name = $request->nama;
        $user->image = $request->file('image')->store(
            'assets/gallery', 'public'
        );
        $user->username = $request->nisn;
        $user->password = bcrypt($request->nisn);
        $user->remember_token = Str::random(60);
        $user->save();
        
        // insert table siswa
        $request->request->add(['user_id' => $user->id]);
        $data = $request->all();
        $data['image'] = $request->file('image')->store(
            'assets/gallery', 'public'
        );

        $siswa = Siswa::create($data);
        
        // Tambahkan siswa ke kelas yang dipilih
        if ($request->kelas_id) {
            // Dapatkan tahun akademik aktif
            $thnAkademik = Thnakademik::where('status', 'Aktif')->first();
            
            if ($thnAkademik) {
                $siswa->kelas()->attach($request->kelas_id, [
                    'thnakademik_id' => $thnAkademik->id,
                    'semester' => $thnAkademik->semester,
                    'status_aktif' => true
                ]);
            } else {
                // Jika tidak ada tahun akademik aktif, gunakan tahun pertama
                $thnAkademik = Thnakademik::orderBy('id', 'asc')->first();
                if ($thnAkademik) {
                    $siswa->kelas()->attach($request->kelas_id, [
                        'thnakademik_id' => $thnAkademik->id,
                        'semester' => $thnAkademik->semester,
                        'status_aktif' => true
                    ]);
                }
            }
        }
        
        return redirect('/admin/siswa')->with('status', 'Data Berhasil Ditambahkan');
    }

    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Siswa::with('kelasAktif')->findOrFail($id);
        $matapelajarans = Mapel::all();

        return view('pages.admin.siswa.detail', [
            'item' => $item,
            'matapelajarans' => $matapelajarans
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item = Siswa::with('kelasAktif')->findOrFail($id);
        $kelas = \App\Kelas::all();

        return view('pages.admin.siswa.edit', [
            'item' => $item,
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
    public function update(SiswaRequest $request, $id)
    {
        $data = $request->all();
        
        $siswa = Siswa::findOrFail($id);

        $user = User::findOrFail($siswa->user_id);
        $user->role = 'siswa';
        $user->name = $request->nama;
        $user->save();
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store(
                'assets/gallery', 'public'
            );
        } else {
            // hapus item
            unset($data['image']);
        }

        $siswa->update($data);
        
        // Update relasi kelas
        if ($request->kelas_id) {
            // Dapatkan tahun akademik aktif
            $thnAkademik = Thnakademik::where('status', 'Aktif')->first();
            
            if ($thnAkademik) {
                $siswa->kelas()->sync([
                    $request->kelas_id => [
                        'thnakademik_id' => $thnAkademik->id,
                        'semester' => $thnAkademik->semester,
                        'status_aktif' => true
                    ]
                ]);
            } else {
                // Jika tidak ada tahun akademik aktif, gunakan tahun pertama
                $thnAkademik = Thnakademik::orderBy('id', 'asc')->first();
                if ($thnAkademik) {
                    $siswa->kelas()->sync([
                        $request->kelas_id => [
                            'thnakademik_id' => $thnAkademik->id,
                            'semester' => $thnAkademik->semester,
                            'status_aktif' => true
                        ]
                    ]);
                }
            }
        }

        return redirect('/admin/siswa')->with('status', 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Siswa::findOrFail($id);
        $item->delete();

        $hapus_siswa = $item->user_id;
        User::where('id', $hapus_siswa)->delete();
        Absensisiswa::where('siswa_id', $id)->delete();

        return redirect('/admin/siswa')->with('status', 'Data berhasil Dihapus');
    }

    public function profile()
    {
        return view('pages.admin.siswa.profile');
    }

    public function profileedit($id)
    {
        $profile = Siswa::findOrFail($id);
        return view('pages.admin.siswa.profileedit');
    }

    public function nilai(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->mapel()->attach($request->mapel, ['uts' => $request->uts, 'uas' => $request->uas, 'status' => $request->status]);

        return redirect('admin/siswa/'.$id.'/show')->with('status', 'Nilai Berhasil Ditambahkan');
    }

    public function nilaitambah($id, $idmapel)
    {
        $item = Siswa::findOrFail($id);
        $nilai = $item->mapel()->findOrFail($idmapel);
        $mapel = Mapel::all();

        return view('pages.admin.siswa.editnilai', [
            'item' => $item,
            'nilai' => $nilai,
            'mapel' => $mapel
        ]);
    }

    public function nilaiupdate(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id); 
        $siswa->mapel()->updateExistingPivot($request->mapel, ['uts' => $request->uts, 'uas' => $request->uas, 'status' => $request->status]);

        return redirect('admin/siswa/'.$id.'/show')->with('status', 'Nilai Berhasil Ditambahkan');
    }

    public function lihatNilai()
    {
        $item = Auth::user()->siswa;
        $mapel = Mapel::all();
        $thnakademik = Thnakademik::all();

        return view('pages.admin.siswa.nilai', [
            'item' => $item,
            'mapel' => $mapel,
            'thnakademik' => $thnakademik
        ]);
    }

    public function jadwal()
    {
        $items = Jadwalmapel::all();
        return view('pages.admin.siswa.jadwal', compact('items'));
    }

    public function exportExcel() 
    {
        return Excel::download(new SiswaExport, 'Siswa.xlsx');
    }

    public function importExcel(Request $request)
    {
        // Excel::import(new SiswaImport, $request->file('DataSiswa'));

        $file = $request->file('file');
        // dd($file);
        $namaFile = $file->getClientOriginalName();
        $file->move('DataSiswa', $namaFile);

        Excel::import(new SiswaImport, public_path('/DataSiswa/'.$namaFile));

        return redirect('/admin/siswa')->with('status', 'Data Berhasil Ditambahkan');
    }

    public function exportPdf()
    {
        // Meningkatkan batas waktu eksekusi untuk proses ekspor PDF
        ini_set('max_execution_time', 300); // Menambah batas waktu menjadi 5 menit
        
        // Get data siswa urutkan berdasarkan kelas dan nama
        $siswa = Siswa::with(['kelasAktif' => function($query) {
                $query->orderBy('nama_kelas');
            }])
            ->select('siswas.*')
            ->join('siswa_kelas', 'siswas.id', '=', 'siswa_kelas.siswa_id')
            ->join('kelas', 'siswa_kelas.kelas_id', '=', 'kelas.id')
            ->orderBy('kelas.nama_kelas')
            ->orderBy('siswas.nama')
            ->get();
        
        $pdf = PDF::loadView('export.siswapdf', ['siswa' => $siswa]);
        
        // Menggunakan setting yang lebih efisien
        $pdf->setPaper('a4', 'landscape');
        $pdf->setOptions(['dpi' => 100, 'defaultFont' => 'sans-serif']);
        
        return $pdf->download('siswa.pdf');
    }

    public function exportNilaiPdf($id)
    {
        // Meningkatkan batas waktu eksekusi
        ini_set('max_execution_time', 300);
        
        $siswa = Siswa::find($id);
        $matapelajarans = Mapel::all();
        
        $pdf = PDF::loadView('export.nilaisiswapdf',
            ['siswa' => $siswa, 'matapelajarans' => $matapelajarans]
        );
        
        // Mengoptimalkan rendering PDF
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions(['dpi' => 100, 'defaultFont' => 'sans-serif']);
        
        return $pdf->download('nilaisiswa.pdf');
    }

    public function cetakNilai() 
    {
        // Meningkatkan batas waktu eksekusi
        ini_set('max_execution_time', 300);
        
        $siswa = Auth::user()->name;
        $matapelajarans = Mapel::all();
        
        $pdf = PDF::loadView('export.nilaipdf',
            ['siswa' => $siswa, 'matapelajarans' => $matapelajarans]
        );
        
        // Mengoptimalkan rendering PDF
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOptions(['dpi' => 100, 'defaultFont' => 'sans-serif']);
        
        return $pdf->download('nilaisiswa.pdf');
    }

    public function timeZone($location) 
    {
        return date_default_timezone_set($location);
    }

    public function absen() 
    {
        $this->timeZone('Asia/Jakarta');
        $user_id = Auth::user()->id;
        $date = date("Y-m-d");
        $cek_absen = Absensisiswa::where(['user_id' => $user_id, 'tanggal' => $date])
                            ->get()
                            ->first();
        if(is_null($cek_absen)) {
            $info = array(
                "status" => "Anda Belum Mengisi Absen Hari Ini",
                "btnIn" => ""
            );
        } else {
            $info = array(
                "status" => "Absensi Hari Ini Telah Berakhir",
                "btnIn" => "disabled"
            );
            // $info = array(
            //     "status" => "Jangan Lupa Absen Keluar",
            //     "btnIn" => "disabled",
            //     "btnOut" => ""
            // );
        } 

        $items = Absensisiswa::where('user_id', $user_id)->orderBy('id', 'DESC')->paginate(10);
        return view('pages.admin.siswa.absen', compact('items', 'info'));
    }

    public function absenpros(Request $request)
    {
        $this->timeZone('Asia/Jakarta');
        $user_id = Auth::user()->id;
        $date = date("Y-m-d");
        $time = date("H:i:s");
        $note = $request->note;

        $absen = new Absensisiswa;
        
        // Absen Masuk
        if (isset($request["btnIn"])) {
            // Cek Double Data
            $cek_double = $absen->where(['tanggal' => $date, 'user_id' => $user_id])
                    ->count();
            if($cek_double > 0) {
                return redirect()->back();
            }
            $absen->create([
                'user_id' => $user_id,
                'tanggal' => $date,
                'time_in' => $time,
                'note' => $note]);
            return redirect()->back(); 
        } 
        // Absen Keluar
        // elseif (isset($request["btnOut"])) {
        //     $absen->where(['tanggal' => $date, 'user_id' => $user_id])
        //             ->update([
        //                 'time_out' => $time,
        //                 'note' => $note]);
        //     return redirect()->back();
        // }
        // return $request->all(); 
    }
}
