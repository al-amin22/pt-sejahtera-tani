<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataUang extends Model
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
