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
 * @property int $user_id
 * @property string|null $image_path
 * @property-read \App\Models\User $user
 * @method static Builder|Animal animalsFiltered(array $parameters)
 * @method static Builder|Animal onSale()
 * @method static Builder|Animal whereImagePath($value)
 * @method static Builder|Animal whereUserId($value)
 * @mixin \Eloquent
 */
class Animal extends Model
{
    use HasFactory;

    private const VALID_FILTERS = [
        'type' => 'breeds.type_id',
        'breed' => 'animals.breed_id',
        'sale_status' => 'animals.sale_status'
    ];

    protected $appends = ['price_TTC'];

    protected $fillable = [
        'name',
        'age',
        'description',
        'price_ht',
        'sale_status',
        'breed_id',
        'image_path',
        'user_id'
    ];

    public function breed(): BelongsTo
    {
        return $this->belongsTo(Breed::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSaleStatusAttribute(): string
    {
        return $this->attributes['sale_status'] ? 'en vente' : 'vendu';
    }

    public function getPriceTTCAttribute(): float
    {
        $vatRates = $this->user->countryVatRate->vat_rate;
        $priceTTC = $this->price_ht * $vatRates;
        return round($priceTTC, 2);
    }

    public function scopeAnimalsFiltered(Builder $qb, array $parameters): Builder
    {
        $qb->select('animals.*')
            ->with(['breed.type'])
            ->join('breeds', 'animals.breed_id', '=', 'breeds.id');

        foreach ($parameters as $key => $value) {
            if (array_key_exists($key, self::VALID_FILTERS)) {
                $qb->where(self::VALID_FILTERS[$key], $value);
            }
        }

        return $qb;
    }

    public function scopeOnSale(Builder $qb): Builder
    {
        $qb->where('sale_status', '=', 1);
        return $qb;
    }
}
