<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenderPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'tender_id',
        'document_name',
        'document_type',
        'description',
        'file_path',
    ];

    public function tender(): BelongsTo
    {
        return $this->belongsTo(Tender::class);
    }
}
