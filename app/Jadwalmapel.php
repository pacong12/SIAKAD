<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jadwalmapel extends Model
{
    protected $fillable = [
        'mapel_id', 'guru_id', 'kelas_id', 'hari', 'jam_mulai', 'jam_selesai'
    ];

    protected $hidden = [];

    public function mapel()
    {
        return $this->belongsTo(Mapel::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
    
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
