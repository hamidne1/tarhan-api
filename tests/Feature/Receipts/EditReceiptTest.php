<?php


namespace Tests\Feature\Receipts;


use App\Models\Order;
use App\Models\Receipt;
use Tests\TestCase;

class EditReceiptTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * @var $order
     */
    protected $order;

    /**
     * set data property
     *
     * @param array $override
     * @return EditReceiptTest
     */
    protected function setData($override = [])
    {
        $this->order = create(Order::class);
        $this->data = raw(Receipt::class,
            array_merge($override, [
                    'order_id' => $this->order->id
                ]
            )
        );

        return $this;
    }

    /**
     * send the request to store the order
     *
     * @param null $receiptId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function update($receiptId = null)
    {
        $receiptId = $receiptId ?: create(Receipt::class, [
            'order_id' => $this->order->id
        ])->id;

        return $this->adminLogin()->putJson(
            route('order.receipts.update', [$this->order->id, $receiptId]), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function an_guest_can_not_edit_receipt()
    {
        $this->putJson(
            route('order.receipts.update', [1, 1]), []
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_edit_receipt()
    {
        $this->customerLogin()->putJson(
            route('order.receipts.update', [1, 1]), []
        )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_price_for_receipt()
    {
        $this->setData(['price' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('price');

        $this->setData(['price' => 'asdflkjf'])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('price');
    }

    # </editor-fold>

    /** @test */
    public function it_update_receipt_in_database()
    {
        $this->setData();

        $receipt = create(Receipt::class, [
            'order_id' => $this->order->id
        ]);

        $this->update($receipt->id)
            ->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertDatabaseHas('receipts', $this->data);

    }
}