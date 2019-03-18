<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Illuminate\Support\Arr;
use Tests\TestCase;

class EditCategoryTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return EditCategoryTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Category::class, $override);

        return $this;
    }

    /**
     * send the request to store the category
     *
     * @param null $categoryId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function update($categoryId = null)
    {
        $categoryId = $categoryId ?: create(Category::class);

        return $this->adminLogin()->putJson(
            route('categories.update', $categoryId), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function an_guest_can_not_edit_catalog()
    {
        $this->putJson(
            route('categories.update', 1), []
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_edit_catalog()
    {
        $this->customerLogin()->putJson(
            route('categories.update', 1), []
        )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_title_for_category()
    {
        $this->setData(['title' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');

        $this->setData(['title' => create(Category::class)->title])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function it_not_validation_error_if_same_title_send_to_update_method()
    {
        $category = create(Category::class);
        $this->setData(['title' => $category->title])
            ->update($category->id)
            ->assertStatus(200);
    }

    /** @test */
    public function it_required_the_valid_label_for_category()
    {
        $this->setData(['label' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('label');

        $this->setData(['label' => create(Category::class)->label])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('label');

    }

    /** @test */
    public function it_not_validation_error_if_same_label_send_to_update_method()
    {
        $category = create(Category::class);
        $this->setData(['label' => $category->label])
            ->update($category->id)
            ->assertStatus(200);
    }

    # </editor-fold>

    /** @test */
    public function it_store_category_in_database()
    {
        $this->setData()
            ->update()
            ->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertDatabaseHas('categories', Arr::except($this->data, 'catalog_id'));
    }

}
