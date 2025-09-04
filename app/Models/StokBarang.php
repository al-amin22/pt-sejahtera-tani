<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokBarang extends Model
{
    use HasFactory;
    protected $table = 'stok_barang';
    protected $fillable = ['nama_barang', 'stok'];
}
