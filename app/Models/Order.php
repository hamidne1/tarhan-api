<?php

namespace App\Models;

/**
 * @property integer id
 * @property User user
 *
 * @property \Illuminate\Support\Collection receipts
 */
class Order extends Model {

    /**
     * {@inheritDoc}
     */
    protected $guarded = [
        'id', 'user_id'
    ];

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /**
     * order user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * order receipts
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receipts()
    {
        return $this->hasMany(Receipt::class);
    }

    /**
     * order transactions
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, Receipt::class);
    }

    # </editor-fold>



}
