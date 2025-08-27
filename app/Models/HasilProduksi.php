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
}
