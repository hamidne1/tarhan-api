<?php

use Faker\Generator as Faker;

$factory->define(App\Models\TariffOption::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'icon' => $faker->title,
        'type' => $faker->randomElement(
            \App\Enums\OptionTypeEnum::values()
        ),
        'tariff_id' => function () {
            return factory(\App\Models\Tariff::class)->create()->id;
        }
    ];
});
