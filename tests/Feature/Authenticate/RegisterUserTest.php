<?php

namespace Tests\Feature\Authenticate;

use App\Models\Token;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterUserTest extends TestCase
{
    use RefreshDatabase;

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return RegisterUserTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(User::class, $override);

        return $this;
    }

    /**
     * send the request to register the user
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function register()
    {
        return $this->postJson(
            route('register'), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_mobile()
    {
        $this->setData(['mobile' => null])
            ->register()
            ->assertStatus(422)
            ->assertJsonValidationErrors('mobile');
    }


    /** @test */

    public function it_require_the_none_empty_name()
    {
        $this->setData(['name' => null])
            ->register()
            ->assertStatus(422)
            ->assertJsonValidationErrors('name');

    }


    /** @test */

    public function it_require_the_none_empty_last_name()
    {
        $this->setData(['last_name' => null])
            ->register()
            ->assertStatus(422)
            ->assertJsonValidationErrors('last_name');

    }


    # </editor-fold>


        /** @test */
    public function it_store_new_customer_into_database_and_return_access_token_to_front_end()
    {
        $user = $this->setData()->data;
        $this->postJson(
            route('register'), $user
        )->assertJsonStructure(['data']);
        $this->assertDatabaseHas('users', $user);
        $this->assertCount(1,Token::all());
    }


}
