<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    use HasFactory;

    protected $table = 'karyawan';
    protected $fillable = ['nama', 'jobdesk'];

    /**
     * Relasi ke AbsensiKaryawan (One to Many).
     */
    public function absensiKaryawan()
    {
        return $this->hasMany(AbsensiKaryawan::class, 'karyawan_id');
    }
}
