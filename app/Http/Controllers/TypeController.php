<?php

namespace App\Http\Controllers;

use App\Models\Type;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as httpResponse;

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
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255'
        ]);
        Type::create($validatedData);

        return response()->json([], httpResponse::HTTP_OK);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        $type = Type::find($id);

        return Inertia::render('Type/Edit', [
            'type' => $type,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $type = Type::find($id);
        $type->update($validatedData);

        return response()->json([], httpResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        $type = Type::find($id);

        if (!$type) {
            return response()->json(['message' => 'Animal non trouvé'], httpResponse::HTTP_NOT_FOUND);
        }

        $type->delete();

        return response()->json(['message' => 'Animal supprimé avec succès'], httpResponse::HTTP_OK);
    }
}
