<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Tests\TestCase;

class GetCategoryTest extends TestCase {


    /** @test */
    public function it_see_category_in_route_categories_index()
    {
        $category = create(Category::class);
        $this->getJson(route('categories.index'))
            ->assertStatus(200)
            ->assertSee($category->title);
    }

    /** @test */
    public function it_see_category_in_route_categories_index_in_this_format()
    {
        create(Category::class);

        $this->getJson(route('categories.index'))
            ->assertJsonStructure(
                [
                    'data' => [
                        [
                            'id', 'title', 'label', 'slug', 'catalog_id'
                        ]
                    ]
                ]
            );
    }
}
