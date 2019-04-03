<?php

namespace Tests\Feature\Fields;

use App\Models\Category;
use App\Models\Field;
use Illuminate\Support\Arr;
use Tests\TestCase;

class EditFieldTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * @var Category $category
     */
    protected $category;

    /**
     * set data property
     *
     * @param array $override
     * @return EditFieldTest
     */
    protected function setData($override = [])
    {
        $this->category = create(Category::class);
        $this->data = raw(Field::class, array_merge($override, [
            'category_id' => $this->category->id
        ]));

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
                route('fields.update', $fieldId), $this->data
            );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="Security">   ##----------------------------------------------------#

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

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_title_for_field()
    {

        $this->setData(['title' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function it_can_take_the_valid_icon_for_field()
    {
        $this->setData(['icon' => null])
            ->update()
            ->assertJsonMissingValidationErrors('icon');

        $this->setData(['icon' => 424])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('icon');
    }

    # </editor-fold?


    /** @test */
    public function it_update_field_in_database()
    {
        $this->setData()
            ->update()
            ->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertDatabaseHas('fields', Arr::except($this->data, 'category_id'));
    }
}
