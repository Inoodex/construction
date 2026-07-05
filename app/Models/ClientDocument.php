<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientDocument extends Model
{
    protected $fillable = [
        'client_id', 'title', 'file_path', 'type',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }
}
