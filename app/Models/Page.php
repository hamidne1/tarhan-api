<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Page
 *
 * @property \Illuminate\Support\Collection widgets
 * @property \Illuminate\Support\Collection contexts
 * @package App\Models
 * @method static |\Illuminate\Database\Eloquent\Builder filter(\App\Filters\PageFilter $filter)
 */
class Page extends Model {
    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $guarded = [
        'id'
    ];


    const With = ['contexts', 'widgets'];

    #-------------------------------------##   <editor-fold desc="The Scoping">   ##----------------------------------------------------#

    /**
     * send builder to filter object and apply that
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Filters\PageFilter $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, \App\Filters\PageFilter $filters)
    {
        return $filters->apply($query);
    }

    # </editor-fold>


    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /**
     * page widgets
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function widgets()
    {
        return $this->hasMany(Widget::class);
    }

    /**
     * page contexts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contexts()
    {
        return $this->hasMany(Context::class);
    }
    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Methods">   ##----------------------------------------------------#

    /**
     * add new widget to page
     *
     * @param $widget
     * @return \Illuminate\Database\Eloquent\Model|Page
     */
    public function addWidget($widget)
    {
        return $this->widgets()
            ->create($widget);
    }

    /**
     * check the page has widget or not
     *
     * @return bool
     */
    public function hasWidget()
    {
        return !!$this->widgets()->exists();
    }

    /**
     * add new context to page
     *
     * @param $context
     * @return \Illuminate\Database\Eloquent\Model|Page
     */
    public function addContext($context)
    {
        return $this->contexts()
            ->create($context);
    }

    /**
     * check the page has context or not
     *
     * @return bool
     */
    public function hasContext()
    {
        return !!$this->contexts()->exists();
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Mutator">   ##----------------------------------------------------#

    /**
     * create slug from slug
     *
     * @param $value
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = \Illuminate\Support\Str::slug($value);
    }


    # </editor-fold>

}
