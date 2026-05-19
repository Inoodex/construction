<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\SettingController;

Route::get('/', function () {
    return redirect()->route('tyro-login.login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->name('tyro-dashboard.index');

Route::prefix('dashboard/settings')->name('admin.settings.')->group(function () {
    Route::get('/', [SettingController::class, 'index'])->name('index');
    Route::post('/update', [SettingController::class, 'update'])->name('update');
});

// Role Management Overrides
use App\Http\Controllers\Admin\RoleController as LocalRoleController;
Route::prefix('dashboard/roles')->name('tyro-dashboard.roles.')->group(function () {
    Route::get('/', [LocalRoleController::class, 'index'])->name('index');
    Route::get('/create', [LocalRoleController::class, 'create'])->name('create');
    Route::post('/', [LocalRoleController::class, 'store'])->name('store');
    Route::get('{id}/edit', [LocalRoleController::class, 'edit'])->name('edit');
    Route::put('{id}', [LocalRoleController::class, 'update'])->name('update');
    Route::post('{id}/toggle', [LocalRoleController::class, 'toggleStatus'])->name('toggle');
    Route::delete('{id}', [LocalRoleController::class, 'destroy'])->name('destroy');
});

// Marketing - Lead Management
use App\Http\Controllers\Admin\Marketing\LeadController;
Route::prefix('dashboard/marketing')->name('admin.marketing.')->group(function () {
    Route::get('leads', [LeadController::class, 'index'])->name('leads.index')->middleware('can:view-leads');
    Route::get('leads/create', [LeadController::class, 'create'])->name('leads.create')->middleware('can:create-lead');
    Route::post('leads', [LeadController::class, 'store'])->name('leads.store')->middleware('can:create-lead');
    Route::get('leads/{lead}/edit', [LeadController::class, 'edit'])->name('leads.edit')->middleware('can:update-lead');
    Route::put('leads/{lead}', [LeadController::class, 'update'])->name('leads.update')->middleware('can:update-lead');
    Route::delete('leads/{lead}', [LeadController::class, 'destroy'])->name('leads.destroy')->middleware('can:delete-lead');
});