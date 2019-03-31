<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Order::class, function (Faker $faker) {
    return [
        'user_id' => function () {
            return factory(\App\Models\User::class)->create()->id;
        },
        'price' => rand(1000, 40000),
        'title' => $faker->sentence,
        'description' => null,
    ];
});
