<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Catalog::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->title,
        'label' => $faker->unique()->sentence,
    ];
});
