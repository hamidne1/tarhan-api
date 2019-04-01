<?php

namespace Tests\Feature\Orders;

use App\Models\Order;
use App\Models\Tariff;
use Tests\TestCase;

class CreateOrderTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return CreateOrderTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Order::class, $override);

        return $this;
    }

    /**
     * send the request to store the order
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function store()
    {
        return $this->customerLogin()->postJson(
            route('orders.store'), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function an_guest_can_not_create_new_order()
    {
        $this->postJson(
            route('orders.store'), []
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_admin_can_not_create_new_order()
    {
        $this->adminLogin()->postJson(
            route('orders.store'), []
        )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_tariff_id_for_order()
    {
        $this->setData(['tariff_id' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('tariff_id');
    }

    /** @test */
    public function it_can_take_the_description_for_order()
    {
        $this->setData(['description' => null])
            ->store()
            ->assertJsonMissingValidationErrors('description');
    }

    # </editor-fold>

    /** @test */
    public function it_store_order_in_database()
    {
        $tariff = create(Tariff::class);
        $this->setData([
            'tariff_id' => $tariff->id
        ])->store();

        $this->assertDatabaseHas('orders', [
            'description' => $this->data['description'],
            'price' => $tariff->payment,
        ]);
    }

    /** @test
     */
    public function it_store_new_receipt_for_order_after_order_created()
    {
        $tariff = create(Tariff::class);
        $this->setData([
            'tariff_id' => $tariff->id
        ])->store();

        $this->assertDatabaseHas('receipts', [
            'price' => ceil($tariff->payment * 0.5),
            'order_id' => Order::all()->last()->id
        ]);
    }
}
