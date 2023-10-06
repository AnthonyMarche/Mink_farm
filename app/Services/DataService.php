<?php

namespace App\Services;

use App\Models\Type;
use App\Models\Breed;

class DataService
{
    public function getTypeAndRaceData(): array
    {
        $types = Type::with('breed')->get();
        $breeds = Breed::with('type')->get();

        return [
            'types' => $types,
            'breeds' => $breeds,
        ];
    }
}
