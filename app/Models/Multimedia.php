<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Multimedia extends Model
{
    /**
     * The Portfolio that this multimedia belongs to.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function portfolio(){

        return $this->belongsTo(Portfolio::class);

    }
}
