<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rfq extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'rfq_number',
        'title',
        'description',
        'issue_date',
        'closing_date',
        'status',
        'created_by',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'closing_date' => 'date',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(RfqItem::class);
    }

    public function vendors(): HasMany
    {
        return $this->hasMany(RfqVendor::class);
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }

    public function awardedQuotation()
    {
        return $this->hasOne(Quotation::class)->where('is_winner', true);
    }

    public function totalEstimated(): float
    {
        return $this->items->sum(fn ($i) => $i->quantity * 0);
    }
}
