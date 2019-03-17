<?php

namespace Tests\Feature\Authenticate;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterUserTest extends TestCase {
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


    # </editor-fold>


    /** @test */
    public function it_store_new_customer_into_database_and_return_access_token_to_front_end()
    {

    }
}
