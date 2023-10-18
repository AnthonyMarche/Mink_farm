<?php

namespace App\Models;

use Database\Factories\AnimalFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\Animal
 *
 * @property int $id
 * @property string $name
 * @property int $age
 * @property string|null $description
 * @property string $price_ht
 * @property string $sale_status
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $breed_id
 * @property-read Breed $breed
 * @property-read float $price_t_t_c
 * @method static AnimalFactory factory($count = null, $state = [])
 * @method static Builder|Animal newModelQuery()
 * @method static Builder|Animal newQuery()
 * @method static Builder|Animal query()
 * @method static Builder|Animal whereAge($value)
 * @method static Builder|Animal whereBreedId($value)
 * @method static Builder|Animal whereCreatedAt($value)
 * @method static Builder|Animal whereDescription($value)
 * @method static Builder|Animal whereId($value)
 * @method static Builder|Animal whereName($value)
 * @method static Builder|Animal wherePriceHt($value)
 * @method static Builder|Animal whereSaleStatus($value)
 * @method static Builder|Animal whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Animal extends Model
{
    use HasFactory;

    private const VALID_FILTERS = [
        'type' => 'breeds.type_id',
        'breed' => 'animals.breed_id',
        'sales_status' => 'animals.breed_id'
    ];

    private const VALID_ORDER = [
        'created_at' => 'animals.created_at',
        'name' => 'animals.name',
        'price_ht' => 'animals.price_ht'
    ];

    protected $appends = ['price_TTC'];

    protected $fillable = [
        'name',
        'age',
        'description',
        'price_ht',
        'sale_status',
        'breed_id',
        'image_path'
    ];

    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class);
    }

    public function getSaleStatusAttribute(): string
    {
        return $this->attributes['sale_status'] ? 'en vente' : 'vendu';
    }

    public function getPriceTTCAttribute(): float
    {
        $priceTTC = $this->attributes['price_ht'] * 1.20;
        return round($priceTTC, 2);
    }

    public function getAnimalsFiltered(array $fields, $orderBy): Collection|array
    {
        $qb = Animal::with(['breed.type'])
            ->select('animals.*')
            ->join('breeds', 'animals.breed_id', '=', 'breeds.id');

        foreach ($fields as $key => $value) {
            if (array_key_exists($key, self::VALID_FILTERS)) {
                $qb->where(self::VALID_FILTERS[$key], $value);
            }
        }

        $qb->orderBy(self::VALID_ORDER[$orderBy], $orderBy === 'created_at' ? 'DESC' : 'ASC' );
        return $qb->get();
    }
}
