<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    protected $table = 'coa';

    protected $fillable = [
        'kode',
        'nama',
        'jenis',
        'saldo_awal',
    ];

    public function detailJurnal()
    {
        return $this->hasMany(DetailJurnal::class, 'coa_id');
    }
}
