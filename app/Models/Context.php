<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static|self findOrFail(int $id)
 * @method static|self create(array $validated)
 */
class Context extends Model {
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

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /**
     * context page
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * context category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Mutator">   ##----------------------------------------------------#

    /**
     * create slug from the title of product
     *
     * @param $value
     */
    public function setSlugAttribute($value)
    {
        $this->attributes['slug'] = \Illuminate\Support\Str::slug($value);
    }


    # </editor-fold>

}
