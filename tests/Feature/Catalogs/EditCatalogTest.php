<?php

namespace Tests\Feature\Catalogs;

use App\Models\Catalog;
use Tests\TestCase;

class EditCatalogTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return EditCatalogTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Catalog::class, $override);

        return $this;
    }

    /**
     * send the request to store the catalog
     *
     * @param null $catalogId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function update($catalogId = null)
    {
        $catalogId = $catalogId ?: create(Catalog::class)->id;

        return $this->adminLogin()->putJson(
            route('catalogs.update', $catalogId), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function an_guest_can_not_edit_catalog()
    {
        $this->putJson(
            route('catalogs.update', 1), []
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_edit_catalog()
    {
        $this->customerLogin()->putJson(
            route('catalogs.update', 1), []
        )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_title_for_catalog()
    {
        $this->setData(['title' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');

        $catalog = create(Catalog::class);
        $this->setData(
            [
                'title' => create(Catalog::class)->title
            ]
        )->update($catalog->id)
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function it_required_the_valid_label_for_catalog()
    {
        $this->setData(['label' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('label');

        $catalog = create(Catalog::class);
        $this->setData(
            [
                'label' => create(Catalog::class)->label
            ]
        )->update($catalog->id)
            ->assertStatus(422)
            ->assertJsonValidationErrors('label');
    }

    # </editor-fold>

    /** @test */
    public function it_update_catalog_in_database()
    {
        $this->setData()
            ->update()
            ->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertDatabaseHas('catalogs', $this->data);
    }
}
