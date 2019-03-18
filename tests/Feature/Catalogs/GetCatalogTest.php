<?php

namespace Tests\Feature\Catalogs;

use App\Models\Catalog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetCatalogTest extends TestCase {
    use RefreshDatabase;

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
                            'id', 'title', 'label', 'slug', 'level', 'parent_id', 'description'
                        ]
                    ]
                ]
            );
    }

}
