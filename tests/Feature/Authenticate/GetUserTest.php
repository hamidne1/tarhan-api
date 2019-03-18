<?php

namespace Tests\Feature\Authenticate;

use App\Models\User;
use Tests\TestCase;

class GetUserTest extends TestCase {

    /** @test */
    public function a_guest_can_not_see_own_information()
    {
        $this->getJson(
            route('customer')
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_user_can_see_own_information()
    {
        $user = create(User::class);

        $this->customerLogin($user)->getJson(route('customer'))
            ->assertStatus(200)
            ->assertJsonStructure(
                [
                    'data' => [
                        'id', 'name', 'mobile'
                    ]
                ]
            );
    }
}
