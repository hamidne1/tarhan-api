<?php


namespace App\Services;


use App\Models\Order;
use App\Models\Receipt;

class ReceiptService {

    /**
     * create new receipt
     *
     * @param Order $order
     * @return \Illuminate\Database\Eloquent\Model|Receipt
     */
    public function store(Order $order)
    {
        return $order->receipts()
            ->create([
                'price' => ceil($order->price * 0.5)
            ]);
    }

}