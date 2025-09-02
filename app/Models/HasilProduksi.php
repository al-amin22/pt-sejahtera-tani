<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HasilProduksi extends Model
{
    protected $table = 'hasil_produksi';

    protected $fillable = [
        'jenis_hasil',
        'jumlah',
        'absensi_karyawan_id',
        'satuan',
        'keterangan',
    ];

    public function absensiKaryawan()
    {
        return $this->belongsTo(AbsensiKaryawan::class, 'absensi_karyawan_id');
    }

    public function absensi()
    {
        return $this->hasOneThrough(
            Absensi::class,         // model tujuan
            AbsensiKaryawan::class, // model perantara
            'id',                   // FK di absensi_karyawan → hasil_produksi.absensi_karyawan_id
            'id',                   // FK di absensi → absensi_karyawan.absensi_id
            'absensi_karyawan_id',  // FK di hasil_produksi
            'absensi_id'            // FK di absensi_karyawan
        );
    }
}
