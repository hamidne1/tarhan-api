<?php

namespace Tests\Feature\Fields;

use App\Models\Category;
use App\Models\Category_field;
use App\Models\Field;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetFieldTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @var $data
     */
    protected $data;

    /** @test */
    public function it_see_fields_in_route_fields_index()
    {
        create(Category_field::class);
        $field = Field::first();
        $category = Category::first();
        $this->adminLogin()->getJson(route('fields.show',$category->id))
         ->assertSee($field->title);
    }


    /** @test */
    public function it_see_catalog_in_route_catalogs_index_in_this_format()
    {
        create(Category_field::class);
        $category = Category::first();
        $this->adminLogin()->getJson(route('fields.show',$category->id))
            ->assertJsonStructure(
                [
                    'data' => [
                        [
                            'id', 'title', 'icon'
                        ]
                    ]
                ]
            );
    }
}
