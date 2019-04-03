<?php

use Faker\Generator as Faker;

$factory->define(App\Models\Receipt::class, function (Faker $faker) {
    return [
        'price' => rand(100, 432400),
        'status' => \App\Enums\ReceiptStatusEnum::UnPaid,
        'image' => null,
        'order_id' => function () {
            return factory(\App\Models\Order::class)->create()->id;
        }
    ];
});
