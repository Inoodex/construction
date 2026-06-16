<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\CostOverrunService;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('alerts:check-cost-overruns', function () {
    $this->info('Checking budgets for cost overruns...');
    $service = app(CostOverrunService::class);
    $counts = $service->checkAllBudgets();
    $this->info("Done. Created: {$counts['created']} new alert(s).");
})->purpose('Check all budgets and create cost overrun alerts');
