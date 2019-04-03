<?php

namespace Tests\Feature\Receipts;

use App\Models\Order;
use App\Models\Receipt;
use Tests\TestCase;

class DeleteReceiptTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * send the request to destroy the receipt
     *
     * @param null $orderId
     * @param null $receiptId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function destroy($orderId = null, $receiptId = null)
    {
        $orderId = $orderId ?: create(Order::class)->id;
        $receiptId = $receiptId ?: create(Receipt::class, [
            'order_id' => $orderId
        ])->id;

        return $this->deleteJson(
            route('order.receipts.destroy', [
                $orderId, $receiptId
            ])
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_delete_a_receipt()
    {
        $this->destroy()->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_delete_a_receipt()
    {
        $this->customerLogin()->destroy()->assertStatus(401);
    }

    # </editor-fold>

    /** @test */
    public function an_authenticated_admin_can_delete_a_receipt()
    {
        $order = create(Order::class);
        $receipt = create(Receipt::class, [
            'order_id' => $order->id
        ]);

        $this->adminLogin()
            ->destroy($order->id, $receipt->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('receipts', $receipt->toArray());
    }

}
