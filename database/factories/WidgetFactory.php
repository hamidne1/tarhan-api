<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Widget::class, function (Faker $faker) {
    return [
        'page_id' => function(){
            return factory(\App\Models\Page::class)->create()->id;
        },
        'category_id' => function () {
            return factory(\App\Models\Category::class)->create()->id;
        },
        'col' => $faker->randomDigit,
        'group' => $faker->randomElement(\App\Enums\ContentGroupEnum::values()),
        'title' => $faker->title,
        'alt' => $faker->jobTitle,
        'href' => $faker->url,
        'src' => $faker->imageUrl()
    ];
});
