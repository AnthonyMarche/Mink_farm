<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CountryVatRates extends Model
{
    use HasFactory;

    protected $fillable = [
        'country',
        'vat_rate'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
