<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Tests\TestCase;

class CreateCategoryTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return CreateCategoryTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Category::class, $override);

        return $this;
    }

    /**
     * send the request to store the category
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function store()
    {
        return $this->adminLogin()->postJson(
            route('categories.store'), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function an_guest_can_not_create_new_category()
    {
        $this->postJson(
            route('categories.store'), []
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_create_new_category()
    {
        $this->customerLogin()->postJson(
            route('categories.store'), []
        )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_title_for_category()
    {
        $this->setData(['title' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');

        $this->setData(['title' => create(Category::class)->title])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function it_required_the_valid_label_for_category()
    {
        $this->setData(['label' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('label');

        $this->setData(['label' => create(Category::class)->label])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('label');
    }

    /** @test */
    public function it_nullable_valid_catalog_id()
    {
        $this->setData(['catalog_id' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('catalog_id');

        $this->setData(['catalog_id' => 999])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('catalog_id');
    }

    # </editor-fold>

    /** @test */
    public function it_store_category_in_database()
    {
        $this->setData()
            ->store()
            ->assertStatus(201)
            ->assertJsonStructure([
                'data', 'message'
            ]);

        $this->assertDatabaseHas('categories', $this->data);
    }


}
