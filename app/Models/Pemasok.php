<?php

namespace App\Models;


/**
 * @mixin \Eloquent
 */
class Pemasok extends BaseModel
{
    protected $table = 'pemasok';

    protected $fillable = [
        'nama',
        'kontak',
        'alamat',
        'kota',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'pemasok_id');
    }
}
