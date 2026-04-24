<?php

namespace App\Models;


/**
 * @mixin \Eloquent
 */
class Jurnal extends BaseModel
{
    protected $table = 'jurnal';

    protected $fillable = [
        'tanggal_jurnal',
        'referensi',
        'keterangan',
    ];

    public function detailJurnal()
    {
        return $this->hasMany(DetailJurnal::class, 'jurnal_id');
    }
}
