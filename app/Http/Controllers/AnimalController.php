<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Models\Breed;
use App\Models\Type;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AnimalController extends Controller
{
    public function getTypeAndRace(): Response
    {
        $types = Type::with('breed')->get();
        $breeds = Breed::with('type')->get();

        return Inertia::render('Welcome', [
            'types' => $types,
            'breeds' => $breeds
        ]);
    }

    public function showAnimals(Request $request)
    {
        $selectedType = $request->input('selectedType');
        $selectedBreed = $request->input('selectedBreed');
        $selectedOrderBy = $request->input('selectedOrderBy');

        $animalsQuery = Animal::with(['breed.type'])
            ->select('animal.*')
            ->join('breed', 'animal.breed_id', '=', 'breed.id');

        if ($selectedType !== null) {
            $animalsQuery->where('breed.type_id', $selectedType);
        }

        if ($selectedBreed !== null) {
            $animalsQuery->where('animal.breed_id', $selectedBreed);
        }

        $animalsQuery->orderBy('animal.' . $selectedOrderBy);
        $animals = $animalsQuery->get();

        return response()->json(['animals' => $animals]);
    }
}
