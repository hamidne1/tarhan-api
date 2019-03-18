<?php

namespace Tests\Feature\Catalogs;

use App\Models\Catalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteCatalogTest extends TestCase {
    use RefreshDatabase;

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

    /** @test */
    public function it_can_delete_a_catalog()
    {
        $catalog = create(Catalog::class);

        $this->destroy($catalog->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('catalogs', $catalog->toArray());
    }

}
