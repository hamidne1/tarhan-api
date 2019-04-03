<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * User Class
 *
 * @property integer id
 * @property \Illuminate\Support\Collection $tokens
 * @property \Illuminate\Support\Collection $orders
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

    /**
     * user orders
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Orders()
    {
        return $this->hasMany(Order::class);
    }

    # </editor-fold>



}