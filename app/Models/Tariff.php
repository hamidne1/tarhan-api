<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 * @property integer id
 *
 * @property Category category
 *
 */
class Tariff extends Model {

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $guarded = [
        'id', 'category_id'
    ];

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /**
     * category category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    # </editor-fold>

}
