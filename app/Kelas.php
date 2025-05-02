<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelas extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'nama_kelas', 'tingkat', 'wali_kelas', 'guru_id', 'thnakademik_id', 'deskripsi'
    ];

    protected $hidden = [];

    public function siswa()
    {
        return $this->belongsToMany(Siswa::class, 'siswa_kelas')
                    ->withPivot(['thnakademik_id', 'semester', 'status_aktif'])
                    ->withTimestamps();
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
    
    public function thnakademik()
    {
        return $this->belongsTo(Thnakademik::class);
    }

    public function jadwalmapel()
    {
        return $this->hasMany(Jadwalmapel::class);
    }
}
