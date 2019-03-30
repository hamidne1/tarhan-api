<?php

namespace Tests\Feature\Pages;

use App\Models\Page;
use Tests\TestCase;

class CreatePageTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return CreatePageTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Page::class, $override);

        return $this;
    }

    /**
     * send the request to store the page
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function store()
    {
        return $this->adminLogin()->postJson(
            route('pages.store'), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_create_new_page()
    {
        $this->postJson(
            route('pages.store'), []
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_create_new_post()
    {
        $this->customerLogin()->setData()
            ->postJson(
                route('pages.store'), []
            )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_slug_for_pages()
    {
        $this->setData(['slug' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('slug');

        $this->setData(['slug' => create(Page::class)->slug])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('slug');
    }

    /** @test */
    public function it_store_new_page_into_database()
    {
        $this->setData()
            ->store()
            ->assertStatus(201)
            ->assertJsonStructure(['message', 'data']);

        $this->assertDatabaseHas('pages', $this->data);
    }
}
