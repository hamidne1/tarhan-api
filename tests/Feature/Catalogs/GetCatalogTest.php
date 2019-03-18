<?php

namespace Tests\Feature\Catalogs;

use App\Models\Catalog;
use Tests\TestCase;

class GetCatalogTest extends TestCase {
    /** @test */
    public function it_see_catalog_in_route_catalogs_index()
    {
        $catalog = create(Catalog::class);
        $this->getJson(route('catalogs.index'))
            ->assertStatus(200)
            ->assertSee($catalog->title);
    }

    /** @test */
    public function it_see_catalog_in_route_catalogs_index_in_this_format()
    {
        create(Catalog::class);

        $this->getJson(route('catalogs.index'))
            ->assertJsonStructure(
                [
                    'data' => [
                        [
                            'id', 'title', 'label', 'slug'
                        ]
                    ]
                ]
            );
    }

}
