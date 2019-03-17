<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User Class
 *
 * @property integer id
 * @property \Illuminate\Support\Collection $tokens
 * @method static |User create($data)
 */
class User extends Authenticatable {

    use Notifiable;

    /**
     * {@inheritDoc}
     */
    protected $guarded = [
        'id'
    ];

    /**
     * {@inheritDoc}
     */
    protected $hidden = [
        'password',
    ];

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /**
     * user tokens
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Tokens()
    {
        return $this->hasMany(Token::class);
    }


    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Mutator">   ##----------------------------------------------------#

    /**
     * hash the password attribute
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    # </editor-fold>


}