<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \Illuminate\Database\Eloquent\Builder orderBy(string $column, string $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder orderByDesc(string $column)
 * @method static \Illuminate\Database\Eloquent\Builder with($relations)
 * @mixin \Eloquent
 */
class Karyawan extends BaseModel
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
