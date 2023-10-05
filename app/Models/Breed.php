<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Breed extends Model
{
    use HasFactory;

    protected $table = 'breed';

    protected $fillable = ['name'];

    public function types(): HasMany
    {
        return $this->hasMany(Type::class);
    }

    public function animals(): HasMany
    {
        return $this->hasMany(Animal::class);
    }
}
