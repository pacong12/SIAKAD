<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thnakademik extends Model
{
    protected $fillable = [
        'tahun_akademik', 'semester', 'status'
    ];

    protected $hidden = [];

    public function pg()
    {
        return $this->hasMany('App\Penilaianguru');
    }

    public function mapel()
    {
        return $this->belongsToMany(Mapel::class, 'nilai_siswa')->withPivot(['uts', 'uas', 'status']);
    }

    public function siswa()
    {
        return $this->belongsToMany(Siswa::class);
    }
}
