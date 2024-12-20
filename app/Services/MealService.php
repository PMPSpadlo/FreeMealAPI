<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Area;
use App\Models\Recipe;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

class MealService
{
    /**
     * @throws RequestException
     */
    public function fetchCategories()
    {
        $response = Http::get('https://www.themealdb.com/api/json/v1/1/categories.php')->throw();
        return $this->validateApiResponse($response, 'categories');
    }


    /**
     * @throws RequestException
     */
    public function fetchMealsByCategory(string $categoryName)
    {
        $response = Http::get("https://www.themealdb.com/api/json/v1/1/filter.php?c=$categoryName")->throw();
        return $this->validateApiResponse($response, 'meals');
    }

    /**
     * @throws RequestException
     */
    public function fetchMealDetails(string $mealId)
    {
        $response = Http::get("https://www.themealdb.com/api/json/v1/1/lookup.php?i=$mealId")->throw();
        $meals = $this->validateApiResponse($response, 'meals');
        return $meals[0] ?? null;
    }

    public function syncCategory(array $categoryDetails)
    {
        return Category::updateOrCreate(
            ['name' => $categoryDetails['strCategory']],
            [
                'thumbnail' => $categoryDetails['strCategoryThumb'],
                'description' => $categoryDetails['strCategoryDescription'],
            ]
        );
    }

    public function syncArea(string $areaName)
    {
        return Area::firstOrCreate(['name' => $areaName]);
    }

    public function syncRecipe(array $mealDetails)
    {
        $category = Category::where('name', $mealDetails['strCategory'])->first();
        $area = $this->syncArea($mealDetails['strArea']);

        Recipe::updateOrCreate(
            ['meal_id' => $mealDetails['idMeal']],
            [
                'title' => $mealDetails['strMeal'],
                'instructions' => $mealDetails['strInstructions'],
                'ingredients' => $this->parseIngredients($mealDetails),
                'category_id' => $category ? $category->id : null,
                'area_id' => $area->id,
                'tags' => $mealDetails['strTags'] ? explode(',', $mealDetails['strTags']) : null,
                'thumbnail' => $mealDetails['strMealThumb'],
                'youtube' => $mealDetails['strYoutube'],
                'source' => $mealDetails['strSource'],
                'image_source' => $mealDetails['strImageSource'],
                'creative_commons_confirmed' => $mealDetails['strCreativeCommonsConfirmed'] ?? false,
                'date_modified' => $mealDetails['dateModified'],
            ]
        );
    }




    private function parseIngredients(array $mealDetails)
    {
        return collect($mealDetails)
            ->filter(fn($value, $key) => str_starts_with($key, 'strIngredient') && !empty($value))
            ->mapWithKeys(function ($value, $key) use ($mealDetails) {
                $index = str_replace('strIngredient', '', $key);
                return [$index => [
                    'name' => $value,
                    'measure' => $mealDetails["strMeasure$index"] ?? null,
                ]];
            })
            ->filter(fn($item) => $item['name'] && $item['measure'])
            ->values()
            ->toArray();
    }


    private function validateApiResponse($response, string $key)
    {
        if (!$response->successful() || !isset($response->json()[$key])) {
            throw new \Exception("Invalid API response for key: $key");
        }
        return $response->json()[$key];
    }
}
