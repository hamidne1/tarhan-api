<?php


namespace App\Services;


use App\Models\Order;
use App\Models\Receipt;

class ReceiptService {

    /**
     * create new receipt
     *
     * @param Order $order
     * @param $price
     * @return \Illuminate\Database\Eloquent\Model|Receipt
     */
    public function store(Order $order, $price)
    {
        return $order->receipts()
            ->create([
                'price' => $price
            ]);
    }

}