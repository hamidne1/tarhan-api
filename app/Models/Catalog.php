<?php

namespace App\Models;

/**
 *
 * @property integer id
 * @property integer title
 * @property integer label
 * @property integer slug
 *
 * @property \Illuminate\Support\Collection categories
 *
 * @method static |Catalog create(array $data)
 * @method static |Catalog findOrFail(int $id)
 */
class Catalog extends Model {
    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $guarded = [
        'id', 'slug'
    ];

    #-------------------------------------##   <editor-fold desc="Booting">   ##----------------------------------------------------#

    /**
     * {@inheritDoc}
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($catalog) {
            $catalog->slug = $catalog->label;
        });
        static::updating(function ($catalog) {
            $catalog->slug = $catalog->label;
        });
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /**
     * catalog categories
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Methods">   ##----------------------------------------------------#

    /**
     * catalog categories
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addCategory(array $data)
    {
        return $this->categories()
            ->create($data);
    }

    /**
     * Check catalog category exists or not
     *
     * @return bool
     */
    public function hasCategory()
    {
        return !!$this->categories()->exists();
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Mutator">   ##----------------------------------------------------#

    /**
     * create slug from the label of catalog
     */
    public function setSlugAttribute()
    {
        $this->attributes['slug'] = \Illuminate\Support\Str::slug($this->label);
    }

    # </editor-fold>
}
