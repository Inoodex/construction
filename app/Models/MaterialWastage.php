<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaterialWastage extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'site_id',
        'material_id',
        'quantity',
        'reason',
        'reported_date',
        'reported_by',
    ];

    protected $casts = [
        'quantity' => 'decimal:4',
        'reported_date' => 'date',
    ];

    /**
     * Get the project where the wastage occurred.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the site where the wastage occurred.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class);
    }

    /**
     * Get the material that was wasted.
     */
    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    /**
     * Get the user who reported the wastage.
     */
    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
