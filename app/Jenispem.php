<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jenispem extends Model
{
    protected $fillable = [
        'jenis',
        'nominal',
        'tanggal_mulai',
        'tanggal_selesai'
    ];

    protected $hidden = [];

    public function pembayaran()
    {
        return $this->hasMany('App\Pembayaran');
    }
}
