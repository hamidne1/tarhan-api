<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Category::class, function (Faker $faker) {
    return [
        'title' => $faker->unique()->sentence,
        'label' => $faker->unique()->sentence,
        'catalog_id' => function () {
            return factory(\App\Models\Catalog::class)->create()->id;
        },
    ];
});
