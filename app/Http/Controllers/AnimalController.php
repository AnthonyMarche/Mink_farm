<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnimalRequest;
use App\Models\Animal;
use App\Services\DataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

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
    public function store(AnimalRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        if ($request->image) {
            $imageName = time() . '.' . $request->image->extension();

            $path = $request->file('image')->storeAs(
                'animalsPictures',
                $imageName,
                'public'
            );

            $validatedData['image_path'] = $path;
        }

        $animal = Animal::create($validatedData);

        return Redirect::route('admin.home')
            ->with('message', [
                'text' => $animal->name . ' créé avec succès',
                'type' => 'success'
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Animal $animal): Response
    {
        $data = $this->dataService->getTypeAndRaceData();

        return Inertia::render('Animal/Edit', [
            'animal' => $animal->load('breed.type'),
            'types' => $data['types'],
            'breeds' => $data['breeds']
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Animal $animal, AnimalRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        if ($request->image) {

            if (!is_null($animal->image_path) && Storage::disk('public')->exists($animal->image_path)) {
                Storage::disk('public')->delete($animal->image_path);
                $validatedData['image_path'] = null;
            }

            if ($request->image) {
                $imageName = time() . '.' . $request->image->extension();

                $path = $request->file('image')->storeAs(
                    'animalsPictures',
                    $imageName,
                    'public'
                );

                $validatedData['image_path'] = $path;
            }
        }

        $animal->update($validatedData);

        return Redirect::route('admin.home')
            ->with('message', [
                'text' => $animal->name . ' modifié avec succès',
                'type' => 'success'
            ]);    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Animal $animal): RedirectResponse
    {
        if (!is_null($animal->image_path) && Storage::disk('public')->exists($animal->image_path)) {
            Storage::disk('public')->delete($animal->image_path);
        }

        $animal->delete();

        return redirect()->back()
            ->with('message', [
                'text' => $animal->name . ' supprimé avec succès',
                'type' => 'success'
            ]);
    }
}
