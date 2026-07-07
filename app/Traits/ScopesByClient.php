<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ScopesByClient
{
    public function scopeForClient(Builder $query, ?int $clientId): Builder
    {
        if ($clientId) {
            return $query->where('client_id', $clientId);
        }

        return $query;
    }
}
