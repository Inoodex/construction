<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tender extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'tender_number',
        'title',
        'description',
        'issue_date',
        'close_date',
        'status',
        'created_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'close_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function bids(): HasMany
    {
        return $this->hasMany(TenderBid::class);
    }
}
