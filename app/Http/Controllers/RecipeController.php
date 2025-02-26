<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Recipe::with(['user', 'comments', 'ratings'])->latest()->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('recipes', 'public');
        }

        $recipe = Recipe::create([
            'title' => $request->title,
            'description' => $request->description,
            'ingredients' => $request->ingredients,
            'instructions' => $request->instructions,
            'image' => $imagePath,
            'user_id' => $request->user()->id,
        ]);

        return response()->json($recipe, 201);
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    // {
    //    //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $recipe)
    {
        if ($request->user()->role !== 'admin' && $recipe->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        // Validation
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->only(['title', 'description', 'ingredients', 'instructions']);

        // Handle image update
        if ($request->hasFile('image')) {
            // Delete old image
            if ($recipe->image) {
                Storage::disk('public')->delete($recipe->image);
            }
            // Store new image
            $imagePath = $request->file('image')->store('recipes', 'public');
            $data['image'] = $imagePath;
        }

        $recipe->update($data);

        return response()->json($recipe);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Recipe $recipe)
    {
        if ($request->user()->role !== 'admin' && $recipe->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized');
        }

        // Delete image if exists
        if ($recipe->image) {
            Storage::disk('public')->delete($recipe->image);
        }

        $recipe->delete();

        return response()->noContent();
    }

    public function search($keyword)
    {
        return Recipe::where('title', 'like', "%$keyword%")
            ->orWhere('description', 'like', "%$keyword%")
            ->orWhere('ingredients', 'like', "%$keyword%")
            ->with(['user', 'comments', 'ratings'])
            ->get();
    }
}
