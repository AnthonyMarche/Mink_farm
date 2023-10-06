<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Animal extends Model
{
    use HasFactory;

    protected $table = 'animal';

    protected $appends = ['price_TTC'];

    protected $fillable = ['name', 'age', 'description', 'price_ht', 'sale_status', 'type_id'];

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
}
