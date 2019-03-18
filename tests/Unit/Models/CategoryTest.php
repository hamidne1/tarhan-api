<?php

namespace Tests\Unit\Models;

use App\Models\Catalog;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CategoryTest extends TestCase {

    use WithFaker;

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var Category $category
     */
    protected $category;

    /**
     * @var Catalog $catalog
     */
    protected $catalog;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->catalog = create(Catalog::class);
        $this->category = create(Category::class, [
            'catalog_id' => $this->catalog->id
        ]);
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

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Booting">   ##----------------------------------------------------#

    /** @test */
    public function it_attach_slug_when_creating_attribute_group()
    {
        $this->assertArrayHasKey('slug', $this->category->toArray());
    }

    /** @test */
    public function it_update_slug_after_update_title()
    {
        $this->category->update([
            'label' => $label = $this->faker->sentence
        ]);

        $this->assertEquals($this->category->slug, Str::slug($label));
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /** @test */
    public function it_belongs_to_a_catalog()
    {
        $this->assertEquals(
            $this->category->catalog->id, $this->catalog->id
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Mutator">   ##----------------------------------------------------#

    /** @test */
    public function it_should_create_slug_from_the_label_come_from_front()
    {
        $category = create('App\Models\Category');

        $this->assertEquals(
            $category->slug, Str::slug($category->label)
        );
    }

    # </editor-fold>
}
