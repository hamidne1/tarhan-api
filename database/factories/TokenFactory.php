<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Token::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(\App\Models\User::class);
        },
        'access_token' => \Illuminate\Support\Str::random(60),
        'expire_at' => \Carbon\Carbon::now()
    ];
});
