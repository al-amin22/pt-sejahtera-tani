<?php

namespace App\Models;


/**
 * @mixin \Eloquent
 */
class ArusKas extends BaseModel
{
    protected $table = 'arus_kas';

    protected $fillable = [
        'transaksi_id',
        'jenis',
        'jumlah',
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }
}
