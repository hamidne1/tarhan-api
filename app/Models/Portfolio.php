<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    /**
     * The category that this Portfolio belongs to.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {

        return $this->belongsTo(Category::class);

    }

    /**
     * The multimedia that belong to the this portfolio.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function multimedia()
    {

        return $this->hasMany(Multimedia::class);

    }

    /**
     * add multimedia  to  this portfolio.
     * @param $data
     * @return Model
     */

    public function addMultimedia($data)
    {

        return $this->hasMany(Multimedia::class)->create($data);

    }

}
