<?php


namespace App\Services;


use App\Filters\CategoryFilter;
use App\Models\Category;

class CategoryService {

    #-------------------------------------##   <editor-fold desc="inject filter and config service">   ##----------------------------------------------------#

    /**
     * @var CategoryFilter $filter
     */
    protected $filter;

    /**
     * AttributeService constructor.
     *
     * @param CategoryFilter $filter
     */
    public function __construct(CategoryFilter $filter)
    {
        $this->filter = $filter;
    }

    # </editor-fold>

    public function get()
    {
        return Category::filter($this->filter)->get();
    }

    public function show($id)
    {
        return Category::filter($this->filter)->findOrFail($id);
    }

}