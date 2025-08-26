<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    protected $table = 'detail_transaksi';

    protected $fillable = [
        'transaksi_id',
        'nama_barang',
        'jumlah',
        'harga',
        'satuan',
        'subtotal',
        'tanggal_transaksi',
        'jenis',
        'mata_uang_id',
        'user_id',
        'keterangan',
        'referensi',
        'dari_rekening_id',
        'ke_rekening_id'
    ];

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function mataUang()
    {
        return $this->belongsTo(MataUang::class, 'mata_uang_id');
    }
    public function dariRekening()
    {
        return $this->belongsTo(Rekening::class, 'dari_rekening_id');
    }

    public function keRekening()
    {
        return $this->belongsTo(Rekening::class, 'ke_rekening_id');
    }
}
