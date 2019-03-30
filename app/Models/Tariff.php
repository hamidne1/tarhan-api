<?php

namespace App\Models;

/**
 *
 * @property integer id
 *
 * @property Category category
 * @property \Illuminate\Support\Collection options
 *
 * @method static |Tariff create(array $data)
 * @method static |Tariff findOrFail(int $id)
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
        'id'
    ];

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /**
     * tariff category
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * tariff options
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function options()
    {
        return $this->hasMany(TariffOption::class);
    }

    # </editor-fold>

}
