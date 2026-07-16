<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = [
        'journal_number',
        'date',
        'description',
        'type',
        'status',
        'is_auto',
        'source_type',
        'source_id',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'date' => 'date',
            'is_auto' => 'boolean',
        ];
    }

    public function items()
    {
        return $this->hasMany(JournalEntryItem::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function source()
    {
        return $this->morphTo();
    }

    public function scopeManual($query)
    {
        return $query->where('is_auto', false);
    }

    public function scopeAuto($query)
    {
        return $query->where('is_auto', true);
    }
}
