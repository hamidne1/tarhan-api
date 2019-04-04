<?php

namespace Tests\Unit\Models;

use App\Enums\GateWay\TransactionStatusEnum;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase {
    use WithFaker;

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var Transaction $transaction
     */
    protected $transaction;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->transaction = create(Transaction::class);
    }

    /** @test */
    public function it_should_extends_base_model()
    {
        $this->assertTrue(
            is_subclass_of(
                $this->transaction, 'App\Models\Model'
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
        $this->transaction->update(
            raw(Transaction::class, $guardData)
        );
        $this->assertDatabaseMissing('transactions', $guardData);
    }

    /** @test */
    public function it_should_guarded_the_id_field()
    {
        $this->assertGuard(['id' => 14048343]);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /** @test */
    public function it_belongs_to_a_receipt()
    {
        $receipt_id = create('App\Models\Receipt')->id;

        $transaction = create(Transaction::class, ['receipt_id' => $receipt_id]);

        $this->assertEquals($receipt_id, $transaction->receipt->id);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Method">   ##----------------------------------------------------#

    /** @test */
    public function it_check_the_transaction_is_retry_by_customer()
    {
        $this->assertFalse($this->transaction->isRetry());

        $this->transaction->update([
            'status' => TransactionStatusEnum::Success
        ]);
        $this->assertTrue($this->transaction->isRetry());

        $this->transaction->update([
            'status' => TransactionStatusEnum::Failed
        ]);
        $this->assertTrue($this->transaction->isRetry());

    }

    # </editor-fold>

}
