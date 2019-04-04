<?php

namespace Tests\Feature\Receipts;

use App\Models\Order;
use App\Models\Receipt;
use Tests\TestCase;

class CreateReceiptTest extends TestCase {

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
     * @return CreateReceiptTest
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
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function store()
    {
        return $this->adminLogin()->postJson(
            route('order.receipts.store', $this->order->id), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function an_guest_can_not_create_new_receipt()
    {
        $this->postJson(
            route('order.receipts.store', 1), []
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_create_new_receipt()
    {
        $this->customerLogin()->postJson(
            route('order.receipts.store', 1), []
        )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_price_for_receipt()
    {
        $this->setData(['price' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('price');

        $this->setData(['price' => 'asdflkjf'])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('price');
    }

    # </editor-fold>

    /** @test */
    public function it_store_receipt_in_database()
    {
        $this->setData()
            ->store()
            ->assertStatus(201)
            ->assertJsonStructure([
                'data', 'message'
            ]);

        $this->assertDatabaseHas('receipts', $this->data);

    }

}
