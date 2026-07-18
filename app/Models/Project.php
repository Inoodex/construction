<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'budget',
        'start_date',
        'end_date',
        'status',
        'created_by',
        'client_id',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the user who created the project.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the sites associated with this project.
     */
    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }

    /**
     * Get the tasks associated with this project.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function phases(): HasMany
    {
        return $this->hasMany(Phase::class)->orderBy('order_index');
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(ProjectResource::class);
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(Budget::class);
    }

    public function rodCalculations(): HasMany
    {
        return $this->hasMany(RodCalculation::class);
    }

    public function purchaseOrders(): HasManyThrough
    {
        return $this->hasManyThrough(PurchaseOrder::class, PurchaseRequisition::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function getProgressAttribute(): int
    {
        return (int) round($this->tasks()->avg('progress_percent') ?: 0);
    }

    public function resourceAllocations(): HasManyThrough
    {
        return $this->hasManyThrough(TaskResource::class, Task::class);
    }
}
