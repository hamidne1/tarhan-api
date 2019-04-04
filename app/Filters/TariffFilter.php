<?php

namespace App\Filters;

use App\Models\Tariff;
use Illuminate\Support\Arr;

class TariffFilter extends AbstractFilters {
    /**
     * eager loading the relations
     *
     * @param null $relations
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function with($relations = null)
    {
        $relations = is_array($relations) ? Arr::first($relations) : $relations;
        $relations = explode(',', $relations);

        return $this->builder->with(
            array_intersect($relations, Tariff::With)
        );
    }
}