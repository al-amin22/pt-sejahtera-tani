<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Pemasok;
use App\Models\MataUang;
use App\Models\ArusKas;
use App\Models\DetailTransaksi;

class Transaksi extends Model
{
    protected $table = 'transaksi';

    protected $fillable = [
        'tanggal_transaksi',
        'total',
        'user_id', // pencatat
        'keterangan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class, 'pemasok_id');
    }

    public function mataUang()
    {
        return $this->belongsTo(MataUang::class, 'mata_uang_id');
    }

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class);
    }

    public function arusKas()
    {
        return $this->hasOne(ArusKas::class, 'transaksi_id');
    }

    public function details()
    {
        return $this->hasMany(DetailTransaksi::class, 'transaksi_id');
    }
}
