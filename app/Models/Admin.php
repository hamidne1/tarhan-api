<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{

    /**
     * {@inheritDoc}
     */
    protected $guarded = [
        'id'
    ];

    /**
     * {@inheritDoc}
     */
    public $timestamps = false;

    /**
     * {@inheritDoc}
     */
    protected $hidden = [
        'password',
    ];

}
