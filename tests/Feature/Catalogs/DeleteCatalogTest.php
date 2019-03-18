<?php

namespace Tests\Feature\Catalogs;

use App\Models\Catalog;
use Tests\TestCase;

class DeleteCatalogTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * send the request to destroy the catalog
     *
     * @param null $catalogId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function destroy($catalogId = null)
    {
        $catalogId = $catalogId ?: create(Catalog::class);

        return $this->deleteJson(
            route('catalogs.destroy', $catalogId)
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function an_guest_can_not_delete_catalog()
    {
        $this->destroy()->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_delete_catalog()
    {
        $this->customerLogin()->destroy()->assertStatus(401);
    }

    # </editor-fold>


    /** @test */
    public function it_can_delete_a_catalog()
    {
        $catalog = create(Catalog::class);

        $this->adminLogin()->destroy($catalog->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('catalogs', $catalog->toArray());
    }

}
