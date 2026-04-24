<?php

namespace App\Models;


/**
 * @mixin \Eloquent
 */
class Produk extends BaseModel
{
    protected $table = 'produk';

    protected $fillable = [
        'kode',
        'nama',
        'satuan',
        'harga',
        'pemasok_id',
    ];

    public function detailTransaksi()
    {
        return $this->hasMany(DetailTransaksi::class, 'produk_id');
    }

    public function pemasok()
    {
        return $this->belongsTo(Pemasok::class, 'pemasok_id');
    }
}
