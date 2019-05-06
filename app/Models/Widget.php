<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static |Widget findOrFail(int $id)
 * @method static|Widget create(array $validated)
 */
class Widget extends Model {

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
     * widget page
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function page()
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * widget category
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
