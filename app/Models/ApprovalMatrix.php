<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApprovalMatrix extends Model
{
    use HasFactory;

    protected $fillable = [
        'approval_workflow_id',
        'role_id',
        'min_amount',
        'max_amount',
        'approval_level',
        'is_active',
    ];

    protected $casts = [
        'min_amount' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the approval workflow
     */
    public function workflow()
    {
        return $this->belongsTo(ApprovalWorkflow::class, 'approval_workflow_id');
    }

    /**
     * Get the role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get users with this role who can approve
     */
    public function getApprovers()
    {
        return $this->role
            ->users()
            ->where('is_active', true)
            ->get();
    }
}
