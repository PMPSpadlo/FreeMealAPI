<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Recipe;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $recipeId): RedirectResponse
    {
        $validatedData = $request->validate([
            'comment' => 'required|max:255',
        ]);

        $recipe = Recipe::findOrFail($recipeId);

        $recipe->comments()->create([
            'comment' => $validatedData['comment'],
        ]);

        return redirect()->route('recipes.show', $recipeId)->with('success', 'Comment added successfully.');
    }
}

