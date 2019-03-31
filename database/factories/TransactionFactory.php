<?php

use Faker\Generator as Faker;

$factory->define(\App\Models\Transaction::class, function (Faker $faker) {
    return [
        'order_id' => function(){
            return factory(\App\Models\Order::class)->create()->id;
        },
        'port' => $faker->randomElement(\App\Enums\GateWay\TransactionPortEnum::values()),
        'price' => 10000,
    ];
});
