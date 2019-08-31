<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;


/**
 * Class Admin
 *
 * @package App\Models
 *
 * @property integer      $id
 * @property string       $username
 * @property string       $name
 * @property string       $password
 * @property boolean      $active
 */
class Admin extends Authenticatable implements JWTSubject
{
    use SoftDeletes;

    /**
     * {@inheritDoc}
     */
    protected $guarded = ['id'];

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

    #-------------------------------------##   <editor-fold desc="JWT Methods">   ##----------------------------------------------------#


    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }


    # </editor-fold>
}
