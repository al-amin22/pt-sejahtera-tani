<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemasok extends Model
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
