<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \Illuminate\Database\Eloquent\Builder with($relations)
 * @method static \Illuminate\Database\Eloquent\Builder where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder orderBy($column, $direction = 'asc')
 * @method static mixed sum($column)
 * @method static mixed create(array $attributes = [])
 * @method static mixed first()
 */
class StokBarang extends BaseModel
{
    use HasFactory;
    protected $table = 'stok_barang';
    protected $fillable = ['nama_barang', 'stok'];
}
