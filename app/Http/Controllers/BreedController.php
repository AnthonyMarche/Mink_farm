<?php

namespace App\Http\Controllers;

use App\Http\Requests\BreedRequest;
use App\Models\Breed;
use App\Models\Type;
use App\Services\DataService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

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
    public function store(BreedRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();

        $breed = Breed::create($validatedData);

        return Redirect::route('breeds.index')
            ->with('message', [
                'text' => $breed->name . ' créée avec succès',
                'type' => 'success'
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Breed $breed): Response
    {
        return Inertia::render('Breed/Edit', [
            'breed' => $breed->load('type'),
            'types' => Type::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BreedRequest $request, Breed $breed): RedirectResponse
    {
        $validatedData = $request->validated();
        $breed->update($validatedData);

        return Redirect::route('breeds.index')
            ->with('message', [
                'text' => $breed->name . ' modifiée avec succès',
                'type' => 'success'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Breed $breed): RedirectResponse
    {
        $breed->delete();
        return redirect()->back()
            ->with('message', [
                'text' => $breed->name . ' supprimé avec succès',
                'type' => 'success'
            ]);
    }
}
