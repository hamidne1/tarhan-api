<?php

namespace App\Models;

/**
 *
 * @property integer id
 * @property string title
 * @property string sub_title
 * @property integer category_id
 * @property string icon
 * @property integer price
 * @property integer discount
 *
 * @property string full_title
 * @property string payment
 *
 * @property Category category
 * @property \Illuminate\Support\Collection options
 *
 * @method static |Tariff create(array $data)
 * @method static |Tariff findOrFail(int $id)
 * @method static |\Illuminate\Database\Eloquent\Builder filter(\App\Filters\TariffFilter $categoryFilters)
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

    const With = ['options'];


    #-------------------------------------##   <editor-fold desc="The Scoping">   ##----------------------------------------------------#

    /**
     * send builder to filter object and apply that
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Filters\TariffFilter $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, \App\Filters\TariffFilter $filters)
    {
        return $filters->apply($query);
    }

    # </editor-fold>

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

    #-------------------------------------##   <editor-fold desc="The Accessor">   ##----------------------------------------------------#

    /**
     * create full title base on parents title
     *
     * @return string
     */
    public function getFullTitleAttribute()
    {
        return implode('-', [
            $this->category->catalog->title, $this->category->title, $this->title
        ]);
    }

    /**
     * get tariff payment
     *
     * @return string
     */
    public function getPaymentAttribute()
    {
        return ($this->price - $this->discount);
    }

    # </editor-fold>

}
