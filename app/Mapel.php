<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $fillable = [
        'kode_mapel', 'nama_mapel'
    ];

    protected $hidden = [
        
    ];

    public function guru()
    {
        return $this->belongsToMany(Guru::class);
    }

    public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'nilai_siswa')->withPivot(['uts', 'uas', 'status','thnakademik_id']);       
    }

    // public function siswas()
    // {
    //     return $this->belongsToMany('App\Siswa');
    // }
}
