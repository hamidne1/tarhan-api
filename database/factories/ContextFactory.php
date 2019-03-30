<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Context::class, function (Faker $faker) {
    return [
        'parent_id' => null,
        'page_id' => function () {
            return factory(\App\Models\Page::class)->create()->id;
        },
        'slug' => $faker->slug,
        'icon' => $faker->title,
        'href' => $faker->url,
        'value' => $faker->randomHtml(),
    ];
});
