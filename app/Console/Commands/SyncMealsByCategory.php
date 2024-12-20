<?php

namespace App\Console\Commands;

use App\Services\MealService;
use Illuminate\Console\Command;

class SyncMealsByCategory extends Command
{
    protected $signature = 'app:sync-meals-by-category';
    protected $description = 'Synchronize meals by categories from TheMealDB API.';

    private MealService $mealService;

    public function __construct(MealService $mealService)
    {
        parent::__construct();
        $this->mealService = $mealService;
    }

    public function handle(): void
    {
        $categories = $this->mealService->fetchCategories();

        if (empty($categories)) {
            $this->error('No categories found from API.');
            return;
        }

        foreach ($categories as $category) {
            $categoryName = $category['strCategory'];
            $this->info("Syncing meals for category: $categoryName");

            $meals = $this->mealService->fetchMealsByCategory($categoryName);

            if (empty($meals)) {
                $this->warn("No meals found for category: $categoryName");
                continue;
            }

            foreach ($meals as $meal) {
                $mealDetails = $this->mealService->fetchMealDetails($meal['idMeal']);

                if ($mealDetails) {
                    $this->mealService->syncRecipe($mealDetails);
                    $this->info("Meal synced: {$mealDetails['strMeal']}");
                } else {
                    $this->warn("Failed to fetch details for meal ID: {$meal['idMeal']}");
                }
            }
        }

        $this->info('All meals synchronized.');
    }
}
