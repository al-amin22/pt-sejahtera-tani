<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Shared Eloquent helpers for IDE support.
 *
 * @method static \Illuminate\Database\Eloquent\Builder query()
 * @method static \Illuminate\Database\Eloquent\Builder|static with($relations)
 * @method static \Illuminate\Database\Eloquent\Builder|static orderBy($column, $direction = 'asc')
 * @method static \Illuminate\Database\Eloquent\Builder|static orderByDesc($column)
 * @method static \Illuminate\Database\Eloquent\Builder|static where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|static whereDate($column, $operator, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|static whereYear($column, $operator, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|static whereMonth($column, $operator, $value = null)
 * @method static \Illuminate\Database\Eloquent\Builder|static whereHas($relation, \Closure $callback, $operator = '>=', $count = 1)
 * @method static \Illuminate\Database\Eloquent\Builder|static take($value)
 * @method static mixed create(array $attributes = [])
 * @method static mixed findOrFail($id)
 * @method static mixed first()
 * @method static mixed get($columns = ['*'])
 * @method static mixed all($columns = ['*'])
 * @method static mixed sum($column)
 * @method static mixed updateOrCreate(array $attributes, array $values = [])
 * @method \Illuminate\Database\Eloquent\Relations\HasMany hasMany($related, $foreignKey = null, $localKey = null)
 * @method \Illuminate\Database\Eloquent\Relations\BelongsTo belongsTo($related, $foreignKey = null, $ownerKey = null, $relation = null)
 * @method \Illuminate\Database\Eloquent\Relations\HasOneThrough hasOneThrough($related, $through, $firstKey = null, $secondKey = null, $localKey = null, $secondLocalKey = null)
 * @method bool update(array $attributes = [], array $options = [])
 * @method bool delete()
 * @method bool save(array $options = [])
 */
abstract class BaseModel extends Model
{
}
