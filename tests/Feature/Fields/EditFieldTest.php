<?php

namespace Tests\Feature\Fields;

use App\Models\Category;
use App\Models\Field;
use Illuminate\Support\Arr;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EditFieldTest extends TestCase
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
     * @return EditFieldTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Field::class, $override);
        return $this;

    }

    /**
     * send the request to edit the field
     *
     * @param null $fieldId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function update($fieldId = null)
    {
        $fieldId = $fieldId ?: create(Field::class)->id;

        return $this->adminLogin()
            ->putJson(
                route('fields.update', ['field' => $fieldId]), $this->data
            );
    }

    /** @test */
    public function an_guest_can_not_edit_field()
    {
        $this->putJson(
            route('fields.update', 1), []
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_edit_field()
    {
        $this->customerLogin()->putJson(
            route('fields.update', 1), []
        )->assertStatus(401);
    }

    /** @test */
    public function it_required_the_valid_title_for_field()
    {

        $this->setData(['title' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function it_required_the_valid_icon_for_field()
    {
        $this->setData(['icon' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('icon');
    }

    /** @test */
    public function it_store_field_in_database()
    {
        $this->setData()
            ->update()
            ->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertDatabaseHas('fields', $this->data);
    }
}
