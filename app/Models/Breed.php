<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Breed extends Model
{
    use HasFactory;

    protected $table = 'breed';

    protected $fillable = ['name'];

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class);
    }
}
