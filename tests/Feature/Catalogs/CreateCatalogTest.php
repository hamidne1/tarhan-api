<?php

namespace Tests\Feature\Catalogs;

use App\Models\Catalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateCatalogTest extends TestCase {
    use RefreshDatabase;

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
        return $this->postJson(
            route('catalogs.store'), $this->data
        );
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

    /** @test */
    public function it_required_the_valid_catalog_id()
    {
        $this->setData(['catalog_id' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonMissingValidationErrors('catalog_id');

        $this->setData(['catalog_id' => 999])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('catalog_id');
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
