<?php

namespace Tests\Feature\Models;

use App\Models\Order;
use App\Models\Token;
use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var User $user
     */
    protected $user;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->user = create(User::class);
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
        $this->user->update(
            raw(User::class, $guardData)
        );
        $this->assertDatabaseMissing('users', $guardData);
    }

    /** @test */
    public function it_should_guarded_the_id_field()
    {
        $this->assertGuard(['id' => 14048343]);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /** @test */
    public function it_has_many_tokens()
    {
        $tokens = create(Token::class, [
            'user_id' => $this->user->id
        ], 2);

        $tokens->each(function ($token) {
            $this->assertTrue(
                $this->user->tokens->contains($token)
            );
        });
    }

    /** @test */
    public function it_has_many_orders()
    {
        $orders = create(Order::class, [
            'user_id' => $this->user->id
        ], 2);

        $orders->each(function ($order) {
            $this->assertTrue(
                $this->user->orders->contains($order)
            );
        });
    }


    # </editor-fold>


}
