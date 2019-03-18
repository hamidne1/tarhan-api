<?php

namespace Tests\Feature\Catalogs;

use App\Models\Catalog;
use Tests\TestCase;

class CreateCatalogTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return CreateCatalogTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Catalog::class, $override);

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
            route('catalogs.store'), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function an_guest_can_not_create_new_catalog()
    {
        $this->postJson(
            route('catalogs.store'), []
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_create_new_catalog()
    {
        $this->customerLogin()->postJson(
            route('catalogs.store'), []
        )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_title_for_catalog()
    {
        $this->setData(['title' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');

        $this->setData(
            [
                'title' => create(Catalog::class)->title
            ]
        )->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function it_required_the_valid_label_for_catalog()
    {
        $this->setData(['label' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('label');

        $this->setData(
            [
                'label' => create(Catalog::class)->label
            ]
        )
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('label');
    }

    # </editor-fold>

    /** @test */
    public function it_store_catalog_in_database()
    {
        $this->setData()
            ->store()
            ->assertStatus(201)
            ->assertJsonStructure([
                'data', 'message'
            ]);

        $this->assertDatabaseHas('catalogs', $this->data);
    }
}
