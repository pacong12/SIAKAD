<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\KelasRequest;
use App\Kelas;
use App\Guru;
use App\Thnakademik;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = Kelas::with(['guru', 'thnakademik'])->get();

        return view('pages.admin.kelas.index', [
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
        $thnakademiks = Thnakademik::all();
        
        return view('pages.admin.kelas.create', [
            'gurus' => $gurus,
            'thnakademiks' => $thnakademiks
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KelasRequest $request)
    {
        $data = $request->all();
        
        // Tetapkan nama kelas berdasarkan tingkat
        $data['nama_kelas'] = 'Kelas ' . $data['tingkat'];
        
        // Jika guru_id diberikan, cari nama wali kelas
        if (!empty($data['guru_id'])) {
            $guru = Guru::find($data['guru_id']);
            if ($guru) {
                $data['wali_kelas'] = $guru->nama;
            }
        }
        
        Kelas::create($data);

        return redirect()->route('kelas.index')->with('status', 'Data Kelas Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = Kelas::with(['siswa', 'guru', 'thnakademik'])->findOrFail($id);
        
        // Hitung jumlah siswa
        $countSiswa = $item->siswa()->wherePivot('status_aktif', true)->count();
        
        return view('pages.admin.kelas.show', [
            'item' => $item,
            'countSiswa' => $countSiswa
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
        $item = Kelas::findOrFail($id);
        $gurus = Guru::all();
        $thnakademiks = Thnakademik::all();

        return view('pages.admin.kelas.edit', [
            'item' => $item,
            'gurus' => $gurus,
            'thnakademiks' => $thnakademiks
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(KelasRequest $request, $id)
    {
        $data = $request->all();
        
        // Tetapkan nama kelas berdasarkan tingkat
        $data['nama_kelas'] = 'Kelas ' . $data['tingkat'];
        
        // Jika guru_id diberikan, cari nama wali kelas
        if (!empty($data['guru_id'])) {
            $guru = Guru::find($data['guru_id']);
            if ($guru) {
                $data['wali_kelas'] = $guru->nama;
            }
        } else {
            $data['wali_kelas'] = null;
        }

        $item = Kelas::findOrFail($id);
        $item->update($data);

        return redirect()->route('kelas.index')->with('status', 'Data Kelas Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $item = Kelas::findOrFail($id);
        
        // Periksa apakah ada siswa aktif di kelas ini
        $activeSiswa = $item->siswa()->wherePivot('status_aktif', true)->exists();
        
        if ($activeSiswa) {
            return redirect()->route('kelas.index')->with('error', 'Kelas tidak dapat dihapus karena masih memiliki siswa aktif');
        }
        
        // Hapus relasi siswa_kelas terlebih dahulu
        DB::table('siswa_kelas')->where('kelas_id', $id)->delete();
        
        // Hapus kelas
        $item->delete();

        return redirect()->route('kelas.index')->with('status', 'Data Kelas Berhasil Dihapus');
    }
}
