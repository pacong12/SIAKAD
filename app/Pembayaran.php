<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $fillable = [
        'jenispem_id', 'nisn', 'nama', 'kelas', 'tanggal', 'jum_pemb', 'keterangan', 'status', 'bukti_pembayaran'
    ];

    public function jenispem()
    {
        return $this->belongsTo('App\Jenispem');
    }
}
