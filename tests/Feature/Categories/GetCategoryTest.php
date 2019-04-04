<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use App\Models\Context;
use App\Models\Widget;
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

    /** @test */
    public function it_can_see_widgets_with_options_in_show_of_category_method()
    {
        $category = create(Category::class);

        $widget = create(Widget::class, [
            'category_id' => $category->id
        ]);

        $context = create(Context::class, [
            'category_id' => $category->id
        ]);


        $this->getJson(route('categories.show', [$category->slug, 'with' => 'widgets,contexts']))
            ->assertStatus(200)
            ->assertSee($category->title)
            ->assertSee($widget->title)
            ->assertSee($context->title);
    }
}
