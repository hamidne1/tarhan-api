<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{

    protected $fillable = ['title', 'icon'];
    /**
     * The categories that belong to the field.
     */
    public function categories()
    {
        return $this->belongsToMany('App\Model\Category');
    }
}
