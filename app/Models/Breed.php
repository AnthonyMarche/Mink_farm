<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Breed
 *
 * @property int $id
 * @property string $name
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $type_id
 * @property-read \App\Models\Type $type
 * @method static Builder|Breed newModelQuery()
 * @method static Builder|Breed newQuery()
 * @method static Builder|Breed query()
 * @method static Builder|Breed whereCreatedAt($value)
 * @method static Builder|Breed whereId($value)
 * @method static Builder|Breed whereName($value)
 * @method static Builder|Breed whereTypeId($value)
 * @method static Builder|Breed whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Breed extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'type_id'];

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
}
