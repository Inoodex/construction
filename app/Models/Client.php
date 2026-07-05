<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'company_name', 'contact_person', 'email', 'phone', 'mobile',
        'address', 'tax_id', 'trade_license', 'notes', 'status',
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(ClientContact::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ClientDocument::class);
    }

    public function primaryContact()
    {
        return $this->hasOne(ClientContact::class)->where('is_primary', true);
    }

    public function proposals(): HasMany
    {
        return $this->hasMany(Proposal::class);
    }
}
