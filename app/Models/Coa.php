<?php

namespace App\Models;


/**
 * @mixin \Eloquent
 */
class Coa extends BaseModel
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
