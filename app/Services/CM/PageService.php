<?php


namespace App\Services\CM;


use App\Filters\PageFilter;
use App\Models\Page;

class PageService {

    #-------------------------------------##   <editor-fold desc="inject filter and config service">   ##----------------------------------------------------#

    /**
     * @var PageFilter $filter
     */
    protected $filter;

    /**
     * AttributeService constructor.
     *
     * @param PageFilter $filter
     */
    public function __construct(PageFilter $filter)
    {
        $this->filter = $filter;
    }

    # </editor-fold>

    /**
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function get()
    {
        return Page::filter($this->filter)->get();
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public function show($id)
    {
        return Page::filter($this->filter)->findOrFail($id);
    }
}