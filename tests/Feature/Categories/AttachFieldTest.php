<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use App\Models\Field;
use Tests\TestCase;

class AttachFieldTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var Category $category
     */
    protected $category;

    /**
     * setup
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->category = create(Category::class);
    }

    # </editor-fold>

    /** @test */
    public function it_can_attach_field()
    {
        $fields = create(Field::class, [], 3)->pluck('id');

        $this->postJson(
            route('categories.fields.store', $this->category->id), compact('fields')
        )->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertCount(3, $this->category->fields);
        $fields->each(function ($field) {
            $this->assertTrue($this->category->fields->contains($field));
        });
    }
}
