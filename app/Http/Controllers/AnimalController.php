<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnimalRequest;
use App\Models\Animal;
use App\Services\DataService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as httpResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
    public function index(Request $request): Response
    {
        $data = $this->dataService->getTypeAndRaceData();
        $parameters = $request->all();
        $orderBy = $parameters['orderBy'] ?? 'created_at';

        $animals = Animal::animalsFiltered($parameters)
            ->onSale()
            ->orderBy($orderBy, $orderBy === 'created_at' ? 'DESC' : 'ASC')
            ->get();

        return Inertia::render('Home', [
            'types' => $data['types'],
            'breeds' => $data['breeds'],
            'animals' => $animals
        ]);
    }

    /**
     * Display a listing of the resource for admin.
     */
    public function adminIndex(Request $request): Response
    {
        $data = $this->dataService->getTypeAndRaceData();
        $parameters = $request->all();
        $orderBy = $parameters['orderBy'] ?? 'created_at';

        $animals = Animal::animalsFiltered($parameters)
            ->orderBy($orderBy, $orderBy === 'created_at' ? 'DESC' : 'ASC')
            ->get();

        return Inertia::render('Animal/Index', [
            'types' => $data['types'],
            'breeds' => $data['breeds'],
            'animals' => $animals
        ]);
    }

    /**
     * Get view to create animals
     */
    public function create(): Response
    {
        $data = $this->dataService->getTypeAndRaceData();

        return Inertia::render('Animal/Edit', [
            'types' => $data['types'],
            'breeds' => $data['breeds']
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AnimalRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (!empty($request->image) && $request->image != 'undefined' && $request->image != 'null') {
            $imageName = time() . '.' . $request->image->extension();

            $path = $request->file('image')->storeAs(
                'animalsPictures',
                $imageName,
                'public'
            );

            $data['image_path'] = $path;
        }

        Animal::create($data);

        return response()->json(['message' => 'Animal créé avec succès'], httpResponse::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        $data = $this->dataService->getTypeAndRaceData();
        $animal = Animal::with(['breed.type'])->find($id);

        return Inertia::render('Animal/Edit', [
            'animal' => $animal,
            'types' => $data['types'],
            'breeds' => $data['breeds']
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, AnimalRequest $request): JsonResponse
    {
        $animal = Animal::find($id);

        $data = $request->validated();

        if ($request->image != 'undefined') {

            if (!is_null($animal->image_path) && Storage::disk('public')->exists($animal->image_path)) {
                Storage::disk('public')->delete($animal->image_path);
                $data['image_path'] = null;
            }


            if ($request->image != 'null') {
                $imageName = time() . '.' . $request->image->extension();

                $path = $request->file('image')->storeAs(
                    'animalsPictures',
                    $imageName,
                    'public'
                );

                $data['image_path'] = $path;
            }
        }

        $animal->update($data);

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

        if (!is_null($animal->image_path) && Storage::disk('public')->exists($animal->image_path)) {
            Storage::disk('public')->delete($animal->image_path);
        }

        $animal->delete();

        return response()->json(['message' => 'Animal supprimé avec succès'], httpResponse::HTTP_OK);
    }
}
