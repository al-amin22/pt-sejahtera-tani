<?php

namespace App\Models;


/**
 * @mixin \Eloquent
 */
class MataUang extends BaseModel
{
    protected $table = 'mata_uang';

    protected $fillable = [
        'kode',
        'nama',
        'kurs',
    ];

    public function transaksi()
    {
        return $this->hasMany(Transaksi::class, 'mata_uang_id');
    }
}
