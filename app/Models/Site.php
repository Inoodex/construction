<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Site extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'name',
        'location_address',
        'status',
    ];

    /**
     * Get the project that owns the site.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the tasks associated with this site.
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function siteLogs(): HasMany
    {
        return $this->hasMany(SiteLog::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(SitePhoto::class);
    }
}
