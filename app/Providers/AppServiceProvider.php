<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use App\Models\PurchaseRequisition;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Explicit route model binding for requisitions
        Route::model('purchase_requisition', PurchaseRequisition::class);
        // echo "Booting AppServiceProvider\n";
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            // echo "Checking ability: {$ability} for user: {$user->email}\n";
            // Super-admin bypass
            if (method_exists($user, 'hasRole') && $user->hasRole('super-admin')) {
                return true;
            }

            // Check if Tyro has this privilege
            if (method_exists($user, 'hasPrivilege') && $user->hasPrivilege($ability)) {
                return true;
            }

            // Check if Tyro has this role (some checks might use role name as ability)
            if (method_exists($user, 'hasRole') && $user->hasRole($ability)) {
                return true;
            }

            return null; // Fallback to other gates/policies
        });
    }
}
