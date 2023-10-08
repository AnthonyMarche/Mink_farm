<?php

namespace App\Http\Controllers;

use App\Models\Breed;
use App\Models\Type;
use App\Services\DataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as httpResponse;

class BreedController extends Controller
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

        return Inertia::render('Breed/Index', [
            'types' => $data['types'],
            'breeds' => $data['breeds']
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $types = Type::all();

        return Inertia::render('Breed/Edit', [
            'types' => $types
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type_id' => 'required|exists:types,id',
        ], [
            'type_id.required' => 'Vous devez selectionner un type.',
        ]);

        Breed::create($validatedData);

        return response()->json([], httpResponse::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        $breed = Breed::with('type')->find($id);
        $types = Type::all();

        return Inertia::render('Breed/Edit', [
            'breed' => $breed,
            'types' => $types,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'type_id' => 'required|exists:types,id',
        ], [
            'type_id.required' => 'Vous devez selectionner un type.',
        ]);

        $breed = Breed::find($id);
        $breed->update($validatedData);

        return response()->json([], httpResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $breed = Breed::find($id);

        if (!$breed) {
            return response()->json(['message' => 'Animal non trouvé'], httpResponse::HTTP_NOT_FOUND);
        }

        $breed->delete();

        return response()->json(['message' => 'Animal supprimé avec succès'], httpResponse::HTTP_OK);
    }
}
