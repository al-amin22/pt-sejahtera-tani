<?php

namespace App\Models;


/**
 * @mixin \Eloquent
 */
class DetailJurnal extends BaseModel
{
    protected $table = 'detail_jurnal';

    protected $fillable = [
        'jurnal_id',
        'coa_id',
        'debit',
        'kredit',
        'keterangan',
    ];

    public function jurnal()
    {
        return $this->belongsTo(Jurnal::class, 'jurnal_id');
    }

    public function coa()
    {
        return $this->belongsTo(Coa::class, 'coa_id');
    }
}
