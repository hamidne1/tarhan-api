<?php

namespace Tests\Unit\Models;

use App\Models\Context;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContextTest extends TestCase {
    use WithFaker;

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var Context $context
     */
    protected $context;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->context = create(Context::class);
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
        $this->context->update(
            raw(Context::class, $guardData)
        );
        $this->assertDatabaseMissing('contexts', $guardData);
    }

    /** @test */
    public function it_should_guarded_the_id_field()
    {
        $this->assertGuard(['id' => 14048343]);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /** @test */
    public function it_belongs_to_a_page()
    {

        $page = create('App\Models\Page');
        $context = create(Context::class, ['page_id' => $page->id]);

        $this->assertEquals($page->toArray(), $context->page->toArray());
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Mutator">   ##----------------------------------------------------#

    /** @test */
    public function it_should_slugged_from_the_slug_of_context()
    {
        $context = create(Context::class, [
            'slug' => 'Page-header-ST'
        ]);

        $this->assertEquals(
            $context->slug, 'page-header-st'
        );
    }

    # </editor-fold>

}
