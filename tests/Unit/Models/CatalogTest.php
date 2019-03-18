<?php

namespace Tests\Unit\Models;

use App\Models\Catalog;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class CatalogTest extends TestCase {

    use WithFaker;

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

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
    }

    /** @test */
    public function it_should_extends_base_model()
    {
        $this->assertTrue(
            is_subclass_of(
                $this->catalog, 'App\Models\Model'
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
        $this->catalog->update(
            raw(Catalog::class, $guardData)
        );
        $this->assertDatabaseMissing('catalogs', $guardData);
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
        $this->assertArrayHasKey('slug', $this->catalog->toArray());
    }

    /** @test */
    public function it_update_slug_after_update_title()
    {
        $this->catalog->update([
            'label' => $label = $this->faker->sentence
        ]);

        $this->assertEquals($this->catalog->slug, Str::slug($label));
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /** @test */
    public function it_has_many_categories()
    {
        $catagories = create(Category::class, [
            'catalog_id' => $this->catalog->id
        ]);

        $catagories->each(function ($category) {
            $this->assertTrue($this->catalog->categories->contains($category));
        });
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Methods">   ##----------------------------------------------------#

    /** @test */
    public function it_can_add_new_category()
    {
        $this->catalog->addCategory(
            raw(Category::class)
        );

        $this->assertCount(1, $this->catalog->categories);
    }

    /** @test */
    public function it_check_has_category_state()
    {
        $this->catalog->addCategory(
            raw(Category::class)
        );

        $anotherCatalog = create(Catalog::class);

        $this->assertTrue($this->catalog->hasCategory());
        $this->assertFalse($anotherCatalog->hasCategory());
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Mutator">   ##----------------------------------------------------#

    /** @test */
    public function it_should_create_slug_from_the_label_come_from_front()
    {
        $this->assertEquals(
            $this->catalog->slug, Str::slug($this->catalog->label)
        );
    }

    # </editor-fold>
}
