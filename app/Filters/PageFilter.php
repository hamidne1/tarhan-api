<?php


namespace App\Filters;


use App\Models\Page;

class PageFilter extends AbstractFilters {

    /**
     * eager loading the relations
     *
     * @param null $relations
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function with($relations = null)
    {
        $relations = explode(',', array_unwrap($relations));

        return $this->builder->with(
            array_intersect($relations, Page::With)
        );
    }
}