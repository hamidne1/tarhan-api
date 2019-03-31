<?php

namespace App\Models;

/**
 * @property integer id
 *
 * @property \Illuminate\Support\Collection transactions
 */
class Receipt extends Model {
    /**
     * {@inheritDoc}
     */
    protected $guarded = [
        'id', 'order_id'
    ];


    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /**
     * receipt order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * receipt transactions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    # </editor-fold>
}
