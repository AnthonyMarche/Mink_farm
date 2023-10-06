<?php

namespace App\Http\Controllers;

use App\Models\Animal;
use App\Services\DataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as httpResponse;

class AnimalController extends Controller
{
    private DataService $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    public function showAnimals(): Response
    {
        $data = $this->dataService->getTypeAndRaceData();

        return Inertia::render('Welcome', [
            'types' => $data['types'],
            'breeds' => $data['breeds']
        ]);
    }

    public function getAnimals(Request $request): JsonResponse
    {
        $selectedType = $request->input('selectedType');
        $selectedBreed = $request->input('selectedBreed');
        $selectedOrderBy = $request->input('selectedOrderBy');
        $selectedSaleStatus = $request->input('selectedSaleStatus');

        $animalsQuery = Animal::with(['breed.type'])
            ->select('animal.*')
            ->join('breed', 'animal.breed_id', '=', 'breed.id');

        if ($selectedSaleStatus !== null) {
            $animalsQuery->where('sale_status', $selectedSaleStatus);
        }

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

    public function adminShowAnimals(): Response
    {
        $data = $this->dataService->getTypeAndRaceData();

        return Inertia::render('Dashboard', [
            'types' => $data['types'],
            'breeds' => $data['breeds']
        ]);
    }

    public function deleteAnimal($id): JsonResponse
    {
        $animal = Animal::find($id);

        if (!$animal) {
            return response()->json(['message' => 'Animal non trouvé'], httpResponse::HTTP_NOT_FOUND);
        }

        $animal->delete();

        return response()->json(['message' => 'Animal supprimé avec succès'], httpResponse::HTTP_OK);
    }

}
