<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Services\CostOverrunService;
use App\Models\BankGuarantee;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('alerts:check-cost-overruns', function () {
    $this->info('Checking budgets for cost overruns...');
    $service = app(CostOverrunService::class);
    $counts = $service->checkAllBudgets();
    $this->info("Done. Created: {$counts['created']} new alert(s).");
})->purpose('Check all budgets and create cost overrun alerts');

Artisan::command('bank-guarantees:expire', function () {
    $count = BankGuarantee::active()
        ->where('expiry_date', '<', now()->toDateString())
        ->update(['status' => 'expired']);
    $this->info("Expired {$count} bank guarantee(s).");
})->purpose('Auto-expire past-due active bank guarantees');

use Illuminate\Support\Facades\Schedule;

Schedule::command('bank-guarantees:expire')->daily();
Schedule::command('alerts:check-cost-overruns')->daily();
