<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Multimedia::class, function (Faker $faker) {
    return [
        'portfolio_id' => function(){

        return factory(\App\Models\Portfolio::class)->create()->id;

        },
        'path' => $faker->sentence

    ];
});
