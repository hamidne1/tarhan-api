<?php

namespace App\Models;


/**
 * @property integer id
 * @property mixed title
 * @property string label
 * @property integer level
 * @property string slug
 *
 * @property \Illuminate\Support\Collection children
 * @property \Illuminate\Support\Collection $attributeGroups
 * @property \Illuminate\Support\Collection $optionGroups
 * @property \Illuminate\Support\Collection products
 *
 * @property Catalog catalog
 * @property \Illuminate\Support\Collection tariffs
 * @property \Illuminate\Support\Collection fields
 *
 * @method static |Category findOrFail($category_id)
 * @method static |Category find($category_id)
 * @method static |Category create($data)
 */
class Category extends Model {

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $guarded = [
        'id', 'slug', 'catalog_id'
    ];

    #-------------------------------------##   <editor-fold desc="Booting">   ##----------------------------------------------------#

    /**
     * {@inheritDoc}
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = $category->label;
        });
        static::updating(function ($category) {
            $category->slug = $category->label;
        });
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /**
     * category catalog
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function catalog()
    {
        return $this->belongsTo(Catalog::class);
    }

    /**
     * category tariffs
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tariffs()
    {
        return $this->hasMany(Tariff::class);
    }

    /**
     * category fields
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function fields()
    {
        return $this->belongsToMany(Field::class);
    }

    /**
     * category portfolios
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function portfolios()
    {
        return $this->hasMany(Portfolio::class);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Methods">   ##----------------------------------------------------#

    /**
     * category add new tariff
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addTariff(array $data)
    {
        return $this->tariffs()
            ->create($data);
    }


    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Mutator">   ##----------------------------------------------------#

    /**
     * create slug from the label of category
     */
    public function setSlugAttribute()
    {
        $this->attributes['slug'] = \Illuminate\Support\Str::slug($this->label);
    }

    # </editor-fold>

}
