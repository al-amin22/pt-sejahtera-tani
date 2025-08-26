<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    use HasFactory;

    protected $table = 'rekening';
    protected $fillable = ['nama', 'keterangan'];

    public function transaksiKeluar()
    {
        return $this->hasMany(DetailTransaksi::class, 'dari_rekening_id');
    }

    public function transaksiMasuk()
    {
        return $this->hasMany(DetailTransaksi::class, 'ke_rekening_id');
    }
}
