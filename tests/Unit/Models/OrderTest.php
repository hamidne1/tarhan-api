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
    public function it_has_many_receipts()
    {
        $receipts = create('App\Models\Receipt', [
            'order_id' => $this->order->id
        ], 2);

        $receipts->each(function ($receipt) {
            $this->assertTrue($this->order->receipts->contains($receipt));
        });
    }

    /** @test */
    public function it_has_many_transactions()
    {
        $receipt = create('App\Models\Receipt', [
            'order_id' => $this->order->id
        ]);

        $transactions = create('App\Models\Transaction', [
            'receipt_id' => $receipt->id
        ], 2);

        $transactions->each(function ($transaction) {
            $this->assertTrue(
                $this->order->transactions->contains($transaction)
            );
        });

    }


    # </editor-fold>
}
