<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Portfolio::class, function (Faker $faker) {
    return [
        'category_id' => function () {
            return factory(\App\Models\Category::class)->create()->id;
        },
        'title' => $faker->text,
        'description' => $faker->sentence,
        'link' => $faker->url
    ];
});
