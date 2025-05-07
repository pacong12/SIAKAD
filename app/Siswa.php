<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = [
        'nisn', 'nama', 'tpt_lahir', 'tgl_lahir', 'jns_kelamin', 'agama', 'alamat', 'nama_ortu', 'asal_sklh', 'image', 'user_id'
    ];

    protected $hidden = [];

    public function mapel()
    {
        return $this->belongsToMany(Mapel::class, 'nilai_siswa')->withPivot(['uts', 'uas', 'status', 'thnakademik_id']);
    }

    public function thnakademik()
    {
        return $this->belongsToMany(Thnakademik::class, 'nilai_siswa', 'siswa_id', 'thnakademik_id')
                    ->withPivot(['uts', 'uas', 'status'])
                    ->withTimestamps();
    }
    
    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'siswa_kelas')
                    ->withPivot(['thnakademik_id', 'semester', 'status_aktif'])
                    ->withTimestamps();
    }
    
    public function kelasAktif()
    {
        return $this->belongsToMany(Kelas::class, 'siswa_kelas')
                    ->withPivot(['thnakademik_id', 'semester', 'status_aktif'])
                    ->wherePivot('status_aktif', true)
                    ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function mapels()
    // {
    //     return $this->belongsToMany('App\Mapel');
    // }
    
    // public function ambilNilai()
    // {
    //     $nilai = "";
    //     foreach($this->mapel as $mapel) {
    //         $nilai = $mapel->pivot->nilai;
    //     }

    //     return $nilai;
    // }

    // public function namaMapel()
    // {
    //     $np = "0";
    //     foreach($this->mapel as $mapel) {
    //         $np = $mapel->nama_mapel;
    //     }

    //     return $np;
    // }
}
