<?php

namespace Tests\Feature\Models;

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
        $token = create('App\Models\Token', [
            'user_id' => $this->user->id
        ]);

        $this->assertTrue($this->user->tokens->contains($token));

    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Mutator">   ##----------------------------------------------------#

    /** @test */
    public function it_hash_the_user_password_for_saving_into_database()
    {
        $hash = $this->app->make('hash');
        $this->assertTrue(
            $hash->check('password', $this->user->getAuthPassword())
        );
    }

    # </editor-fold>

}
