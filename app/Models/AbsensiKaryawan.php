<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiKaryawan extends Model
{
    use HasFactory;

    protected $table = 'absensi_karyawan';
    protected $fillable = [
        'absensi_id',
        'karyawan_id',
        'status',
        'jam_masuk',
        'jam_keluar'
    ];

    /**
     * Relasi ke Absensi (Many to One).
     */
    public function absensi()
    {
        return $this->belongsTo(Absensi::class, 'absensi_id');
    }

    /**
     * Relasi ke Karyawan (Many to One).
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function hasilProduksi()
    {
        return $this->hasMany(HasilProduksi::class, 'absensi_karyawan_id');
    }
}
