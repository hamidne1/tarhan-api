<?php

namespace Tests\Feature\Authenticate;

use App\Models\Token;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginUserTest extends TestCase {

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
            route('login'), $this->data
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
            route('login.verify'), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_mobile()
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
    public function it_required_verify_key_for_verify_login_user()
    {
        $this->setData(['verify_key' => null])
            ->verify()
            ->assertStatus(422)
            ->assertJsonValidationErrors('verify_key');
    }

    /** @test */
    public function it_required_the_valid_verify_key_for_logged_user()
    {
        $user = create(User::class, [
            'verify_key' => 4578
        ]);

        $this->setData([
            'mobile' => $user->mobile,
            'verify_key' => 9999
        ])->verify()
            ->assertStatus(422)
            ->assertJsonValidationErrors('verify_key');
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
        $this->assertAuthenticatedAs($user, 'customer');
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
            ])->login();

        $this->setData([
            'mobile' => $user->mobile,
            'password' => 4545
        ])->login()
            ->assertStatus(429)
            ->assertJsonValidationErrors('mobile');
    }


    /** @test */
    public function it_logged_out_and_remove_token()
    {
        $this->customerLogin()
            ->postJson(route('logout'))
            ->assertStatus(200);

        $this->assertFalse($this->isAuthenticated());
        $this->assertEmpty(Token::all());
    }
}
