<?php

namespace App\Models;

/**
 * @property integer id
 * @property mixed title
 * @property string label
 * @property integer level
 * @property string slug
 * @property \Illuminate\Support\Collection children
 * @property \Illuminate\Support\Collection $attributeGroups
 * @property \Illuminate\Support\Collection $optionGroups
 * @property \Illuminate\Support\Collection products
 * @method static |Category findOrFail($category_id)
 * @method static |Category find($category_id)
 * @method static |Category create($data)
 * @method static |\Illuminate\Database\Eloquent\Builder filter(\App\Filters\CategoryFilter $categoryFilters)
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
        'id', 'slug', 'level', 'parent_id'
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

            $category->level = $category->parent_id
                ? static::findOrFail($category->parent_id)->level + 1
                : 1;
        });
//        static::updating(function ($category) {
//            $category->slug = $category->label; //TODO: think about this if need to chnage slug
//        });
        static::deleted(function ($category) {
            foreach (\Illuminate\Support\Collection::wrap($category->children()->get()) as $child)
                /** @var static $child */
                $child->delete();
        });
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Scoping">   ##----------------------------------------------------#

    /**
     * send builder to filter object and apply that
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param \App\Filters\CategoryFilter $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, \App\Filters\CategoryFilter $filters)
    {
        return $filters->apply($query);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /**
     * category parent
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * category children
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Methods">   ##----------------------------------------------------#


    /**
     * add new category to category
     *
     * @param $category
     * @param $parent_id
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function addCategory($category, $parent_id = null)
    {
        return $parent_id
            ? static::findOrFail($parent_id)->children()->create($category)
            : static::create($category);
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
