<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function index(Request $request): View|Factory|Application
    {
        $search = $request->query('search', '');
        $recipes = Recipe::when($search, function ($query, $search) {
            $query->where('title', 'like', '%' . $search . '%');
        })->paginate(10);

        return view('recipes.index', compact('recipes', 'search'));
    }

    public function show($id): View|Factory|Application
    {
        $recipe = Recipe::with('comments')->findOrFail($id);

        return view('recipes.show', compact('recipe'));
    }
}

