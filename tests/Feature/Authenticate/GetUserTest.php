<?php

namespace Tests\Feature\Authenticate;

use App\Models\User;
use Tests\TestCase;

class GetUserTest extends TestCase {


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
