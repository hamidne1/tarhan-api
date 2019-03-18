<?php

namespace Tests\Unit\Models;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryTest extends TestCase {

    use WithFaker, RefreshDatabase;

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var Category $category
     */
    protected $category;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->category = create(Category::class);
    }

    /** @test */
    public function it_should_extends_base_model()
    {
        $this->assertTrue(
            is_subclass_of(
                $this->category, 'App\Models\Model'
            )
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Guarded">   ##----------------------------------------------------#

    /**
     * checking guard data
     *
     * @param array $guardData
     */
    protected function assertGuard(array $guardData)
    {
        $this->category->update(
            raw(Category::class, $guardData)
        );
        $this->assertDatabaseMissing('categories', $guardData);
    }

    /** @test */
    public function it_should_guarded_the_id_field()
    {
        $this->assertGuard(['id' => 14048343]);
    }

    /** @test */
    public function it_should_guarded_the_slug_field()
    {
        $this->assertGuard(['slug' => 'The fake slug value']);
    }

    /** @test */
    public function it_should_guarded_the_level_field()
    {
        $this->assertGuard(['level' => 999]);
    }

    /** @test */
    public function it_should_guarded_the_parent_id_field()
    {
        $this->assertGuard(['parent_id' => 999]);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Booting">   ##----------------------------------------------------#


    /** @test */
    public function it_attach_slug_when_creating_attribute_group()
    {
        $this->assertArrayHasKey('slug', $this->category->toArray());
    }

    /** @test */
    public function it_attach_level_when_creating_attribute_group()
    {
        $this->assertArrayHasKey('level', $this->category->toArray());
    }

    /** @test */
    public function it_not_update_slug_after_update_title()
    {
        $this->category->update([
            'label' => $label = $this->faker->sentence
        ]);

        $this->assertNotEquals($this->category->slug, Str::slug($label));
    }

    /** @test
     */
    public function after_delete_a_category_all_children_also_deleted_at_the_same_time()
    {
        $child = create(Category::class, ['parent_id' => $this->category->id]);
        $childOfTheChild = create(Category::class, ['parent_id' => $child->id]);

        try {
            $this->category->delete();
        } catch (\Exception $e) {
        }

        $this->assertDatabaseMissing('categories', $this->category->toArray());
        $this->assertDatabaseMissing('categories', $child->toArray());
        $this->assertDatabaseMissing('categories', $childOfTheChild->toArray());
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /** @test */
    public function it_belongs_to_a_parent()
    {
        $child = create(Category::class, ['parent_id' => $this->category->id]);

        $this->assertEquals($child->parent->toArray(), $this->category->toArray());
    }

    /** @test */
    public function it_has_many_children()
    {
        $children = create(Category::class, [
            'parent_id' => $this->category->id
        ], 2);

        $children->each(function ($child) {
            $this->assertTrue($this->category->children->contains($child));
        });
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Methods">   ##----------------------------------------------------#

    /** @test */
    public function it_can_add_new_category()
    {
        $this->category->addCategory(
            raw(Category::class), $this->category->id
        );

        $this->assertCount(1, $this->category->children);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Mutator">   ##----------------------------------------------------#

    /** @test */
    public function it_should_create_slug_from_the_label_come_from_front()
    {
        $category = create('App\Models\Category');

        $this->assertEquals(
            $category->slug, str_slug($category->label)
        );
    }

    # </editor-fold>
}
