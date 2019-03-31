<?php

namespace Tests\Unit\Models;

use App\Models\Receipt;
use App\Models\Transaction;
use Tests\TestCase;

class ReceiptTest extends TestCase {
    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var Receipt $receipt
     */
    protected $receipt;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->receipt = create(Receipt::class);
    }

    /** @test */
    public function it_should_extends_base_model()
    {
        $this->assertTrue(
            is_subclass_of(
                $this->receipt, 'App\Models\Model'
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
        $this->receipt->update(
            raw(Receipt::class, $guardData)
        );
        $this->assertDatabaseMissing('receipts', $guardData);
    }

    /** @test */
    public function it_should_guarded_the_id_field()
    {
        $this->assertGuard(['id' => 14048343]);
    }

    /** @test */
    public function it_should_guarded_the_order_id_field()
    {
        $this->assertGuard(['order_id' => 14048343]);
    }
    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /** @test */
    public function it_belongs_to_a_order()
    {
        $order_id = create('App\Models\Order')->id;

        $receipt = create(Receipt::class, ['order_id' => $order_id]);

        $this->assertEquals($order_id, $receipt->order->id);
    }

    /** @test */
    public function it_has_many_transactions()
    {
        $transactions = create(Transaction::class, [
            'receipt_id' => $this->receipt->id
        ], 2);

        $transactions->each(function ($transaction) {
            $this->assertTrue(
                $this->receipt->transactions->contains($transaction)
            );
        });
    }

    # </editor-fold>

}
