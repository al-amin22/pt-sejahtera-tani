<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $fillable = [
        'tanggal',
        'video',
        'foto'
    ];

    /**
     * Relasi ke AbsensiKaryawan (One to Many).
     */
    public function absensiKaryawan()
    {
        return $this->hasMany(AbsensiKaryawan::class, 'absensi_id');
    }
}
