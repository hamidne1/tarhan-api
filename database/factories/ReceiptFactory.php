<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Receipt::class, function (Faker $faker) {
    return [
        'price'=> rand(100, 432400),
        'status' => $faker->randomElement(
            \App\Enums\ReceiptStatusEnum::values()
        ),
        'image' => null,
        'order_id' => function(){
            return factory(\App\Models\Order::class)->create()->id;
        }
    ];
});
