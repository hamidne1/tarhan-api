<?php

namespace Tests\Unit\Models;

use App\Models\Order;
use Tests\TestCase;

class OrderTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var Order $order
     */
    protected $order;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->order = create(Order::class);
    }

    /** @test */
    public function it_should_extends_base_model()
    {
        $this->assertTrue(
            is_subclass_of(
                $this->order, 'App\Models\Model'
            )
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Guarded">   ##----------------------------------------------------#

    /**
     * checking guard data
     *
     * @param array $guardData
     */
    protected function assertGuard(array $guardData)
    {
        $this->order->update(
            raw(Order::class, $guardData)
        );
        $this->assertDatabaseMissing('orders', $guardData);
    }

    /** @test */
    public function it_should_guarded_the_id_field()
    {
        $this->assertGuard(['id' => 14048343]);
    }


    /** @test */
    public function it_should_guarded_the_user_id_field()
    {
        $this->assertGuard(['user_id' => 999]);
    }


    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = create('App\Models\User');

        $order = create(Order::class, [
            'user_id' => $user->id
        ]);

        $this->assertEquals($user->id, $order->user->id);
    }

    /** @test */
    public function it_has_many_transactions()
    {
        $transactions = create('App\Models\Transaction', [
            'order_id' => $this->order->id
        ], 2);

        $transactions->each(function ($transaction) {
            $this->assertTrue($this->order->transactions->contains($transaction));
        });
    }

    /** @test */
    public function it_has_one_shipping()
    {
        $shipping = create('App\Models\Shipping', [
            'order_id' => $this->order->id
        ]);

        $this->assertEquals($this->order->shipping->id, $shipping->id);
    }

    /** @test */
    public function it_has_many_products()
    {
        $product = create('App\Models\Product');

        $this->order->products()->attach([$product->id]);

        $this->assertTrue(
            $this->order->products->contains($product)
        );
    }

    /** @test */
    public function it_has_many_products_with_the_pivot_data()
    {
        $product1 = create('App\Models\Product');
        $product2 = create('App\Models\Product');
        $this->order->products()->attach([
            $data1 = [
                'product_id' => $product1->id,
                'order_id' => $this->order->id,
                'price' => $product1->payment,
                'options_id' => $options1 = create('App\Models\Option', [], 2)->pluck('id')->toArray()
            ],
            $data2 = [
                'product_id' => $product2->id,
                'order_id' => $this->order->id,
                'price' => $product2->payment,
                'options_id' => $options2 = create('App\Models\Option', [], 2)->pluck('id')->toArray()
            ]
        ]);

        $this->assertEquals($data1['price'], $this->order->products->first()->pivot->price);
        $this->assertEquals($data1['options_id'], $this->order->products->first()->pivot->options_id);
//
        $this->assertEquals($data2['price'], $this->order->products->last()->pivot->price);
        $this->assertEquals($data2['options_id'], $this->order->products->last()->pivot->options_id);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Accessor">   ##----------------------------------------------------#

    /** @test */
    public function it_calculate_order_total_price()
    {
        create('App\Models\Shipping', [
            'order_id' => $this->order->id,
            'price' => $price = 1300
        ]);

        $this->assertEquals(
            $this->order->payment + $price, $this->order->total
        );
    }

    # </editor-fold>

}
