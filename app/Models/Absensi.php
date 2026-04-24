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
class Absensi extends BaseModel
{
    use HasFactory;

    protected $table = 'absensi';
    protected $fillable = [
        'tanggal',
        'video',
        'foto'
    ];

    /**
     * Relasi ke AbsensiKaryawan (One to Many).
     */
    public function absensiKaryawan()
    {
        return $this->hasMany(AbsensiKaryawan::class, 'absensi_id');
    }
}
