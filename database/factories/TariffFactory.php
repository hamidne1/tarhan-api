<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Tariff::class, function (Faker $faker) {
    return [
        'title' => $faker->title,
        'sub_title' => $faker->sentence,
        'category_id' => function(){
            return factory(\App\Models\Category::class)->create()->id;
        },
        'icon' => $faker->sentence,
        'price' => $faker->numberBetween(),
        'discount' => 0
    ];
});
