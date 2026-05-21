<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaterialIssueSlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'site_id',
        'issued_to',
        'issue_number',
        'issue_date',
    ];

    protected $casts = [
        'issue_date' => 'date',
    ];

    /**
     * Get the project associated with the issue slip.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the site associated with the issue slip.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the user whom the materials were issued to.
     */
    public function recipient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'issued_to');
    }

    /**
     * Get the items in this issue slip.
     */
    public function items(): HasMany
    {
        return $this->hasMany(MaterialIssueSlipItem::class);
    }
}
