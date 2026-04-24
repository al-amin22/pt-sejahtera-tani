<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \Illuminate\Database\Eloquent\Builder orderBy(string $column, string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder orderByDesc(string $column)
 * @method static \Illuminate\Database\Eloquent\Builder with($relations)
 * @method static static findOrFail($id)
 * @method static static create(array $attributes = [])
 * @mixin \Eloquent
 */
class AbsensiKaryawan extends BaseModel
{
    use HasFactory;

    protected $table = 'absensi_karyawan';
    protected $fillable = [
        'absensi_id',
        'karyawan_id',
        'status',
        'jam_masuk',
        'jam_keluar'
    ];

    /**
     * Relasi ke Absensi (Many to One).
     */
    public function absensi()
    {
        return $this->belongsTo(Absensi::class, 'absensi_id');
    }

    /**
     * Relasi ke Karyawan (Many to One).
     */
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }

    public function hasilProduksi()
    {
        return $this->hasMany(HasilProduksi::class, 'absensi_karyawan_id');
    }
}
