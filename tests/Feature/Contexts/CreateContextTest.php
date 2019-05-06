<?php

namespace Tests\Feature\Contexts;

use App\Models\Context;
use Tests\TestCase;

class CreateContextTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return CreateContextTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Context::class, $override);

        return $this;
    }

    /**
     * send the request to store the context
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function store()
    {
        return $this->adminLogin()->postJson(
            route('contexts.store'), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_create_new_context()
    {
        $this->setData()
            ->postJson(
                route('contexts.store'), []
            )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_create_new_context()
    {
        $this->customerLogin()->setData()
            ->postJson(
                route('contexts.store'), []
            )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_page_for_context()
    {
        $this->setData(['page_id' => null])
            ->store()
            ->assertJsonMissingValidationErrors('page_id');

        $this->setData(['page_id' => 999])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('page_id');
    }

    /** @test */
    public function it_required_the_valid_category_id_for_context()
    {
        $this->setData(['category_id' => null])
            ->store()
            ->assertJsonMissingValidationErrors('category_id');

        $this->setData(['category_id' => 999])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('category_id');
    }

    /** @test */
    public function it_required_the_valid_slug_for_context()
    {
        $this->setData(['slug' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('slug');

        $this->setData(['slug' => create(Context::class)->slug])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('slug');
    }

    /** @test */
    public function it_can_take_the_valid_parent_id_for_context()
    {
        $this->setData(['parent_id' => null])
            ->store()
            ->assertJsonMissingValidationErrors('parent_id');

        $this->setData(['parent_id' => 999])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('parent_id');

    }

    /** @test */
    public function it_can_take_the_icon_for_context()
    {
        $this->setData(['icon' => null])
            ->store()
            ->assertJsonMissingValidationErrors('icon');
    }

    /** @test */
    public function it_can_take_the_valid_href_for_context()
    {
        $this->setData(['href' => null])
            ->store()
            ->assertJsonMissingValidationErrors('href');

        $this->setData(['href' => 'string'])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('href');
    }

    /** @test */
    public function it_required_the_valid_value_for_context()
    {
        $this->setData(['value' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('value');
    }

    # </editor-fold>


    /** @test */
    public function it_store_new_context_to_database()
    {
        $this->setData()
            ->store()
            ->assertStatus(201)
            ->assertJsonStructure([
                'message', 'data'
            ]);

        $this->assertDatabaseHas('contexts', [
            'slug' => $this->data['slug']
        ]);
    }


}
