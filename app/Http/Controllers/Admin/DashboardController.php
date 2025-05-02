<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Guru;
use App\Siswa;
use App\Mapel;
use App\Sekolah;
use App\Kelas;
use App\Jadwalmapel;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $items = Sekolah::all();
        $siswa = Siswa::count();
        $guru = Guru::count();
        $mapel = Mapel::count();
        $kelas = Kelas::count();
        
        $pie = [
            'laki' => Siswa::where('jns_kelamin', 'L')->count(),
            'perempuan' => Siswa::where('jns_kelamin', 'P')->count()
        ];
        
        // Data untuk dashboard Guru
        $jadwal = 0;
        $kelasGuru = 0;
        
        if (Auth::user()->role === 'guru') {
            $guru_id = Guru::where('user_id', Auth::user()->id)->first()->id;
            $jadwal = Jadwalmapel::where('guru_id', $guru_id)->count();
            $kelasGuru = Jadwalmapel::where('guru_id', $guru_id)
                        ->distinct('kelas_id')
                        ->count('kelas_id');
        }
        
        return view('pages.admin.dashboard', [
            'items' => $items,
            'siswa' => $siswa,
            'guru' => $guru,
            'mapel' => $mapel,
            'kelas' => $kelas,
            'pie' => $pie,
            'jadwal' => $jadwal,
            'kelasGuru' => $kelasGuru
        ]);
    }
}
