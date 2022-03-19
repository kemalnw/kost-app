<?php

namespace App\Http\QueryFilters\Traits;

use Illuminate\Support\Facades\Schema;

trait Sortable
{
    protected $defaultSortAttr = 'id';
    protected $defaultSortDirection = 'asc';

    /**
     * Sort the entity by the given value
     *
     * @param string $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function sortAttribute(string $value = 'id')
    {
        $attribute = $value ?? $this->defaultSortAttr;
        $direction = strtolower($this->request->sort_direction ?? $this->defaultSortDirection);
        if (!in_array($direction, ['asc', 'desc'])) {
            $direction = $this->defaultSortDirection;
        }

        $model = $this->builder->getModel();
        if (!Schema::hasColumn($model->getTable(), $attribute)) {
            $attribute = $model->getKeyName();
        }

        return $this->builder->orderBy(
            $attribute,
            $direction,
        );
    }
}
