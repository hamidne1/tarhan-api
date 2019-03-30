<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property Tariff tariff
 */
class TariffOption extends Model {
    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $guarded = [
        'id', 'tariff_id'
    ];

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /**
     * tariffOption tariff
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tariff()
    {
        return $this->belongsTo(Tariff::class);
    }

    # </editor-fold>
}
