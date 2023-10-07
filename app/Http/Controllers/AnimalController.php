<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnimalRequest;
use App\Models\Animal;
use App\Services\DataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $data = $this->dataService->getTypeAndRaceData();

        return Inertia::render('Home', [
            'types' => $data['types'],
            'breeds' => $data['breeds']
        ]);
    }

    /**
     * Display a listing of the resource for admin.
     */
    public function adminIndex(): Response
    {
        $data = $this->dataService->getTypeAndRaceData();

        return Inertia::render('Dashboard', [
            'types' => $data['types'],
            'breeds' => $data['breeds']
        ]);
    }

    /**
     * Get animals with different filters
     */
    public function getAnimals(Request $request): JsonResponse
    {
        $selectedType = $request->input('selectedType');
        $selectedBreed = $request->input('selectedBreed');
        $selectedOrderBy = $request->input('selectedOrderBy');
        $selectedSaleStatus = $request->input('selectedSaleStatus');

        $animalsQuery = Animal::with(['breed.type'])
            ->select('animals.*')
            ->join('breeds', 'animals.breed_id', '=', 'breeds.id');

        if ($selectedSaleStatus !== null) {
            $animalsQuery->where('animals.sale_status', $selectedSaleStatus);
        }

        if ($selectedType !== null) {
            $animalsQuery->where('breeds.type_id', $selectedType);
        }

        if ($selectedBreed !== null) {
            $animalsQuery->where('animals.breed_id', $selectedBreed);
        }
        if ($selectedOrderBy === 'created_at') {
            $animalsQuery->orderBy('animals.' . $selectedOrderBy, 'DESC');
        } else {
            $animalsQuery->orderBy('animals.' . $selectedOrderBy);

        }
        $animals = $animalsQuery->get();

        return response()->json(['animals' => $animals]);
    }

    /**
     * Get view to create animals
     */
    public function create(): Response
    {
        $data = $this->dataService->getTypeAndRaceData();

        return Inertia::render('UpdateAnimal', [
            'types' => $data['types'],
            'breeds' => $data['breeds']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AnimalRequest $request): JsonResponse
    {
        Animal::create($request->validated());

        return response()->json(['message' => 'Animal créé avec succès'], httpResponse::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        $data = $this->dataService->getTypeAndRaceData();
        $animal = Animal::with(['breed.type'])->find($id);

        return Inertia::render('UpdateAnimal', [
            'animal' => $animal,
            'types' => $data['types'],
            'breeds' => $data['breeds']
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(AnimalRequest $request, string $id): JsonResponse
    {
        $animal = Animal::find($id);
        $animal->update($request->validated());

        return response()->json(['message' => 'Animal modifié avec succès'], httpResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $animal = Animal::find($id);

        if (!$animal) {
            return response()->json(['message' => 'Animal non trouvé'], httpResponse::HTTP_NOT_FOUND);
        }

        $animal->delete();

        return response()->json(['message' => 'Animal supprimé avec succès'], httpResponse::HTTP_OK);
    }
}
