<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use App\Mapel;
use App\Siswa;
use App\Jadwalmapel;
use App\Thnakademik;
use App\Kelas;
use App\User;
use Illuminate\Http\Request;
use DB;

class NilaiController extends Controller
{
    public function index()
    {
        // Ambil semua kelas yang tersedia
        $kelas = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();
        return view('pages.admin.siswa.masuknilai', compact('kelas'));
    }

    public function proses($kelas_id)
    {
        // Ambil semua siswa yang terdaftar di kelas tersebut
        $kelas = Kelas::findOrFail($kelas_id);
        $pnilai = $kelas->siswa()->wherePivot('status_aktif', true)->get();
        
        // Ambil tahun akademik yang aktif
        $tahunAktif = Thnakademik::where('status', 'aktif')->first();
        
        // Jika tidak ada tahun akademik aktif, ambil yang pertama
        if (!$tahunAktif) {
            $tahunAktif = Thnakademik::first();
        }

        return view('pages.admin.siswa.prosnilai', compact('pnilai', 'kelas', 'tahunAktif'));
    }

    public function nilaiSimpanBatch(Request $request)
    {
        // Validasi input dasar
        $request->validate([
            'mapel_id' => 'required|exists:mapels,id',
            'thnakademik_id' => 'required|exists:thnakademiks,id',
            'kelas_id' => 'required|exists:kelas,id',
            'siswa_id' => 'required|array',
            'uts' => 'required|array',
            'uas' => 'required|array',
            'status' => 'required|array',
        ]);

        // Ambil jumlah siswa
        $jumlahSiswa = count($request->siswa_id);
        
        // Mulai transaksi database
        DB::beginTransaction();
        try {
            // Untuk setiap siswa
            for ($i = 0; $i < $jumlahSiswa; $i++) {
                $siswaId = $request->siswa_id[$i];
                $mapelId = $request->mapel_id;
                
                // Cek apakah nilai sudah ada untuk siswa, mapel, dan tahun akademik yang sama
                $siswa = Siswa::findOrFail($siswaId);
                $existingRecord = $siswa->mapel()
                    ->where('mapel_id', $mapelId)
                    ->wherePivot('thnakademik_id', $request->thnakademik_id)
                    ->first();
                
                // Data nilai untuk disimpan
                $nilaiData = [
                    'uts' => $request->uts[$i],
                    'uas' => $request->uas[$i],
                    'status' => $request->status[$i],
                    'thnakademik_id' => $request->thnakademik_id
                ];
                
                if ($existingRecord) {
                    // Jika sudah ada, update nilai
                    $siswa->mapel()->updateExistingPivot($mapelId, $nilaiData);
                } else {
                    // Jika belum ada, tambahkan nilai baru
                    $siswa->mapel()->attach($mapelId, $nilaiData);
                }
            }
            
            // Commit transaksi jika semua berhasil
            DB::commit();
            return redirect('guru/nilaiProses/' . $request->kelas_id)->with('status', 'Nilai berhasil disimpan untuk semua siswa');
            
        } catch (\Exception $e) {
            // Rollback transaksi jika ada error
            DB::rollback();
            return redirect()->back()->withErrors(['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function detail($id)
    {
        $item = Siswa::findOrFail($id);
        $matapelajarans = Mapel::all();
        $thnakademiks = Thnakademik::all();

        return view('pages.admin.siswa.tambahnilai', compact('item', 'matapelajarans', 'thnakademiks'));
    }

    public function detailNilai($id)
    {
        $item = Siswa::findOrFail($id);
        $matapelajarans = Mapel::all();
        $thnakademiks = Thnakademik::all();

        return view('pages.admin.siswa.detailNilai', compact('item', 'matapelajarans', 'thnakademiks'));
    }

    public function nilai(Request $request, $id)
{
    $siswa = Siswa::findOrFail($id);

    // Pastikan thnakademik_id dikirimkan
    $thnakademikId = $request->thnakademik; // Ambil dari request

    // Cek jika thnakademik_id kosong
    if (!$thnakademikId) {
        return redirect()->back()->withErrors(['thnakademik' => 'Tahun akademik harus dipilih']);
    }

    // Simpan nilai ke dalam mapel_siswa
    $siswa->mapel()->attach($request->mapel, [
        'uts' => $request->uts,
        'uas' => $request->uas,
        'status' => $request->status,
        'thnakademik_id' => $thnakademikId // Gunakan nilai dari request
    ]);

    return redirect('siswa/'.$id.'/nilai')->with('status', 'Nilai Berhasil Ditambahkan');
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

        return redirect('siswa/'.$id.'/nilai')->with('status', 'Nilai Berhasil Ditambahkan');
    }

    public function nilaihapus($id, $idmapel)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->mapel()->detach($idmapel);

        return redirect('siswa/'.$id.'/nilai')->with('status', 'Nilai Berhasil Dihapus');
    }

    public function getNilaiSiswa(Request $request)
    {
        $mapelId = $request->mapel_id;
        $kelasId = $request->kelas_id;
        $thnakademikId = $request->thnakademik_id;
        
        // Validasi input
        if (!$mapelId || !$kelasId || !$thnakademikId) {
            return response()->json(['error' => 'Parameter tidak lengkap'], 400);
        }
        
        // Ambil data siswa dalam kelas
        $kelas = Kelas::findOrFail($kelasId);
        $siswaList = $kelas->siswa()->wherePivot('status_aktif', true)->get();
        
        // Siapkan data response
        $result = [];
        
        foreach ($siswaList as $siswa) {
            // Cari nilai siswa untuk mapel dan tahun akademik yang dipilih
            $nilai = $siswa->mapel()
                ->where('mapel_id', $mapelId)
                ->wherePivot('thnakademik_id', $thnakademikId)
                ->first();
            
            // Siapkan data siswa
            $dataSiswa = [
                'id' => $siswa->id,
                'nisn' => $siswa->nisn,
                'nama' => $siswa->nama,
                'nilai' => null
            ];
            
            // Jika nilai ditemukan, tambahkan ke data siswa
            if ($nilai) {
                $dataSiswa['nilai'] = [
                    'uts' => $nilai->pivot->uts,
                    'uas' => $nilai->pivot->uas,
                    'status' => $nilai->pivot->status
                ];
            }
            
            $result[] = $dataSiswa;
        }
        
        return response()->json($result);
    }

    public function cetakNilai($id)
    {
        $data = Siswa::findOrFail($id);
        $items = Thnakademik::all();
        return view('pages.admin.siswa.cetakNilaiSiswa', compact('items', 'data'));
    }

    public function cetakNilaiPeraka($id, $thnakademik)
    {
        // dd(["Tanggal Awal : ".$tglawal, "Tanggal Akhir : ".$tglakhir]);
        $cetakPeraka = Siswa::findOrFail($id)->thnakademik('tahun_akademik', [$thnakademik]);

        dd($cetakPeraka);

        $pdf = PDF::loadview('export.absenpertanggalpdf', compact('absenPertanggal'));
        return $pdf->download('laporan-absen.pdf');
    }
}
