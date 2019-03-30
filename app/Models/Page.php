<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Page
 *
 * @property \Illuminate\Support\Collection widgets
 * @property \Illuminate\Support\Collection contexts
 * @package App\Models
 * @method static|Page create(array $array)
 * @method static|Page findOrFail(int $id)
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

    /**
     * {@inheritDoc}
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

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
