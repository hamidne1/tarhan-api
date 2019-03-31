<?php

namespace Tests\Feature\Fields;

use App\Models\Category_field;
use App\Models\Field;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateFieldTest extends TestCase
{
    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return CreateFieldTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Category_field::class, $override);

        return $this;
    }

    /**
     * send the request to store the catalog
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function store()
    {
        return $this->adminLogin()->postJson(
            route('fields.store'), $this->data
        );
    }


    /** @test */
    public function an_guest_can_not_create_new_field()
    {
        $this->postJson(
            route('fields.store'), []
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_create_new_field()
    {
        $this->customerLogin()->postJson(
            route('fields.store'), []
        )->assertStatus(401);
    }

    /** @test */
    public function it_required_the_valid_title_for_category()
    {
        $this->setData(['title' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
        }
}
