<?php

namespace Tests\Feature\Options;

use App\Models\Option;
use Tests\TestCase;

class CreateOptionTest extends TestCase {
    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return CreateOptionTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Option::class, $override);

        return $this;
    }

    /**
     * send the request to store the option
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function store()
    {
        return $this->adminLogin()->postJson(
            route('options.store'), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function an_guest_can_not_create_new_option()
    {
        $this->postJson(
            route('options.store'), []
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_create_new_option()
    {
        $this->customerLogin()->postJson(
            route('options.store'), []
        )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_title_for_option()
    {
        $this->setData(['title' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function it_required_the_valid_type_for_option()
    {
        $this->setData(['type' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('type');

        $this->setData(['type' => 'rad'])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('type');
    }

    /** @test */
    public function it_can_take_the_valid_icon_for_option()
    {
        $this->setData(['icon' => null])
            ->store()
            ->assertJsonMissingValidationErrors('icon');
    }


    # </editor-fold>

    /** @test */
    public function it_store_option_in_database()
    {
        $this->setData()
            ->store()
            ->assertStatus(201)
            ->assertJsonStructure([
                'data', 'message'
            ]);

        $this->assertDatabaseHas('options', $this->data);
    }
}
