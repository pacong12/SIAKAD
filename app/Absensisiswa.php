<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Absensisiswa extends Model
{
    protected $table = 'absensisiswa';
    
    protected $fillable = [
        'siswa_id', 'kelas_id', 'tanggal', 'status', 'keterangan', 'guru_id'
    ];

    /**
     * Relasi ke model Siswa
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id');
    }
    
    /**
     * Relasi ke model Kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
    
    /**
     * Relasi ke model User (guru)
     */
    public function guru()
    {
        return $this->belongsTo(User::class, 'guru_id');
    }
    
    /**
     * Mendapatkan status dengan format yang lebih baik
     */
    public function getStatusLabelAttribute()
    {
        switch ($this->status) {
            case 'hadir':
                return 'Hadir';
            case 'sakit':
                return 'Sakit';
            case 'izin':
                return 'Izin';
            case 'alpa':
                return 'Alpa';
            default:
                return ucfirst($this->status);
        }
    }
    
    /**
     * Mendapatkan tanggal dalam format yang lebih baik
     */
    public function getTanggalFormatAttribute()
    {
        return date('d/m/Y', strtotime($this->tanggal));
    }
} 