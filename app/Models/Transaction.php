<?php

namespace App\Models;

/**
 * Class Transaction
 *
 * @property int $id
 * @property string status
 * @property integer amount
 * @property string ref_id
 * @property Receipt receipt
 * @method static | Transaction create($data)
 */
class Transaction extends Model {

    /**
     * {@inheritDoc}
     */
    protected $guarded = [
        'id', 'receipt_id'
    ];


    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /**
     * transaction  receipt
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receipt()
    {
        return $this->belongsTo(Receipt::class);
    }

    # </editor-fold>


    /**
     * checking the transaction state is retry or not
     *
     * @return bool
     */
    public function isRetry()
    {
        return !!in_array($this->status, [
            \App\Enums\GateWay\TransactionStatusEnum::Success,
            \App\Enums\GateWay\TransactionStatusEnum::Failed
        ]);
    }
}
