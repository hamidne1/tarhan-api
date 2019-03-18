<?php

namespace Tests\Feature\Authenticate;

use App\Models\Token;
use App\Models\User;
use Tests\TestCase;

class LoginUserTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data = [];

    /**
     * set data property
     *
     * @param array $override
     * @return LoginUserTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(User::class, $override);

        return $this;
    }

    /**
     * send the request to login the user
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function login()
    {
        return $this->postJson(
            route('customer.login'), $this->data
        );
    }

    /**
     * send the request to verify the user
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function verify()
    {
        return $this->postJson(
            route('customer.login.verify'), $this->data
        );
    }

    /**
     * send the request to logout the user
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function logout()
    {
        return $this->postJson(
            route('customer.logout')
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="Security">   ##----------------------------------------------------#

    /** @test */
    public function an_authenticated_customer_can_not_logged_in_again()
    {
        $this->customerLogin()
            ->login()
            ->assertStatus(200)
            ->assertExactJson([
                'message' => 'already authorized ..!'
            ]);
    }

    /** @test */
    public function an_authenticated_customer_can_not_verify_again()
    {
        $this->customerLogin()
            ->verify()
            ->assertStatus(200)
            ->assertExactJson([
                'message' => 'already authorized ..!'
            ]);
    }

    /** @test */
    public function only_authenticated_customer_can_logout_from_dashboard()
    {
        $this->logout()->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_mobile_for_login()
    {
        $this->setData(['mobile' => null,])
            ->login()
            ->assertStatus(422)
            ->assertJsonValidationErrors('mobile');

        $this->setData(['mobile' => '0913060786'])
            ->login()
            ->assertStatus(422)
            ->assertJsonValidationErrors('mobile');
    }

    /** @test */
    public function it_required_verify_code_for_verify_login_user()
    {
        $this->setData(['verify_code' => null])
            ->verify()
            ->assertStatus(422)
            ->assertJsonValidationErrors('verify_code');
    }

    /** @test */
    public function it_required_mobile_for_verify_login_user()
    {
        $this->setData(['mobile' => null])
            ->verify()
            ->assertStatus(422)
            ->assertJsonValidationErrors('mobile');
    }

    /** @test */
    public function it_required_the_valid_verify_code_for_logged_user()
    {
        $user = create(User::class, [
            'verify_code' => 4578
        ]);

        $this->setData([
            'mobile' => $user->mobile,
            'verify_code' => 9999
        ])->verify()
            ->assertStatus(422)
            ->assertJsonValidationErrors('mobile');
    }

    # </editor-fold>

    /** @test */
    public function it_take_a_access_token_after_logged_in()
    {
        $user = create(User::class, [
            'verify_code' => $code = 4545
        ]);

        $this->setData([
            'mobile' => $user->mobile,
            'verify_code' => $code
        ])->verify()
            ->assertStatus(200)
            ->assertJsonStructure([
                'data'
            ]);

        $this->assertCount(1, $user->tokens);
    }

    /** @test */
    public function it_locked_if_try_to_wrong_verify_code_after_some_times()
    {
        $user = create(User::class, [
            'verify_code' => $code = 4545
        ]);

        for ($i = 0; $i < 5; $i++)
            $this->setData([
                'mobile' => $user->mobile,
                'verify_code' => 9999
            ])->verify();

        $this->setData([
            'mobile' => $user->mobile,
            'verify_code' => 4545
        ])->verify()
            ->assertStatus(429)
            ->assertJsonValidationErrors('mobile');
    }

    /** @test */
    public function it_logged_out_and_remove_token()
    {
        $this->customerLogin()
            ->logout()
            ->assertStatus(200);

        $this->assertFalse($this->isAuthenticated());
        $this->assertEmpty(Token::all());
    }
}
