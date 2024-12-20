<?php

namespace App\Console\Commands;

use App\Services\MealService;
use Illuminate\Console\Command;

class SyncCategories extends Command
{
    protected $signature = 'app:sync-categories';
    protected $description = 'Synchronize categories from TheMealDB API.';

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

        foreach ($categories as $categoryDetails) {
            $this->mealService->syncCategory($categoryDetails);
            $this->info("Category synced: {$categoryDetails['strCategory']}");
        }

        $this->info('All categories synchronized.');
    }
}

