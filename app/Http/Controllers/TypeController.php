<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeRequest;
use App\Models\Type;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $types = Type::all();

        return Inertia::render('Type/Index', [
            'types' => $types,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Type/Edit');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TypeRequest $request): RedirectResponse
    {
        $validatedData = $request->validated();
        $type = Type::create($validatedData);

        return Redirect::route('types.index')
            ->with('message', [
                'text' => $type->name . ' créé avec succès',
                'type' => 'success'
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Type $type): Response
    {
        return Inertia::render('Type/Edit', [
            'type' => $type,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TypeRequest $request, Type $type): RedirectResponse
    {
        $validatedData = $request->validated();
        $type->update($validatedData);

        return Redirect::route('types.index')
            ->with('message', [
                'text' => $type->name . ' modifié avec succès',
                'type' => 'success'
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type): RedirectResponse
    {
        $type->delete();
        return redirect()->back()
            ->with('message', [
                'text' => $type->name . ' supprimé avec succès',
                'type' => 'success'
            ]);
    }
}
