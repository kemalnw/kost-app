<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Filterable
{
    /**
     * Apply all relevant filters.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $abstract
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter(Builder $query, string $abstract): Builder
    {
        return app($abstract)->apply($query);
    }
}
