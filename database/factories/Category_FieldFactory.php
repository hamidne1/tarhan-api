<?php

use App\Models\Category;
use App\Models\Category_field;
use App\Models\Field;
use Faker\Generator as Faker;

$factory->define(Category_field::class, function (Faker $faker) {
    return [
        'category_id' => factory(Category::class)->create()->id,
        'field_id' => factory(Field::class)->create()->id
    ];
});
