<?php

namespace App\Http\QueryFilters;

use Illuminate\Http\Request;
use App\Concern\RequestFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Http\QueryFilters\Traits\Sortable;

class RoomFilter extends RequestFilter
{
    use Sortable;

    /**
     * Initialize a new filter instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function __construct(Request $request)
    {
        parent::__construct($request);

        $this->defaultSortAttr = 'price';
    }

    /**
     * Filter the room by the given string.
     *
     * @param  string  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function search(string $value = ''): Builder
    {
        $value = strtolower($value);

        return $this->builder->where(function($query) use($value) {
            return $query->whereRaw('lower(name) like (?)', ["%{$value}%"])
                ->orWhereRaw('lower(location) like (?)', ["%{$value}%"])
                ->orWhereRaw('lower(price) like (?)', ["%{$value}%"]);
        });
    }

}
