<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
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
