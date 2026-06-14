<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApprovalHistory extends Model
{
    use HasFactory;

    protected $table = 'approval_history';

    protected $fillable = [
        'approval_id',
        'approval_level',
        'approved_by',
        'status',
        'comment',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];

    /**
     * Get the approval record
     */
    public function approval()
    {
        return $this->belongsTo(Approval::class);
    }

    /**
     * Get the user who approved/rejected
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
