<?php

namespace Tests\Feature\Fields;

use App\Models\Category;
use App\Models\Category_field;
use App\Models\Field;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateFieldTest extends TestCase
{
    use RefreshDatabase;
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

        $this->data = raw(Field::class, $override);
        $this->data = array_merge($this->data, ['category_id' => create(Category::class)->id]);
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
    public function it_required_the_valid_title_for_field()
    {

        $this->setData(['title' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }


    /** @test */
    public function it_required_the_valid_icon_for_field()
    {
        $this->setData(['icon' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('icon');
    }

    /** @test */
    public function it_nullable_valid_category_id()
    {
        $this->setData();
        $this->data['category_id'] = null;
        $this->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('category_id');

        $this->setData(['category_id' => 999]);
        $this->data['category_id'] = null;
        $this->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('category_id');
    }

    /** @test */
    public function it_store_field_in_database()
    {
        $this->setData()
            ->store()
            ->assertStatus(201)
            ->assertJsonStructure([
                'data', 'message'
            ]);
        $this->assertDatabaseHas('fields', array_except($this->data, ['category_id']));
    }
}
