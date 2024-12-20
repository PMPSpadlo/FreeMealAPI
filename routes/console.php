<?php

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Define the schedule
app(Schedule::class)->command('app:sync-categories')->daily();
app(Schedule::class)->command('app:sync-meals-by-category')->daily();
