<?php

namespace App\Models;

/**
 * Class Token
 *
 * @property integer id
 *
 * @method static |Token create($data)
 * @method static|\Illuminate\Database\Eloquent\Builder where($column, $operator = null, $value = null, $boolean = 'and')
 */
class Token extends Model {

    /**
     * {@inheritDoc}
     */
    protected $guarded = [
        'id', 'user_id'
    ];

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /**
     * token user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function User()
    {
        return $this->belongsTo(User::class);
    }

    # </editor-fold>∑
}

