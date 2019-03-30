<?php

namespace Tests\Unit\Models;

use App\Models\Page;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PageTest extends TestCase {
    use  WithFaker;

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var Page $page
     */
    protected $page;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->page = create(Page::class);
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
        $this->page->update(
            raw(Page::class, $guardData)
        );
        $this->assertDatabaseMissing('pages', $guardData);
    }

    /** @test */
    public function it_should_guarded_the_id_field()
    {
        $this->assertGuard(['id' => 14048343]);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /** @test */
    public function it_has_many_widget()
    {
        $widgets = create('App\Models\Widget', ['page_id' => $this->page->id], 2);

        $widgets->each(function ($widget) {
            $this->assertTrue($this->page->widgets->contains($widget));
        });
    }

    /** @test */
    public function it_has_many_context()
    {
        $contexts = create('App\Models\Context', ['page_id' => $this->page->id], 2);

        $contexts->each(function ($context) {
            $this->assertTrue($this->page->contexts->contains($context));
        });
    }
    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Methods">   ##----------------------------------------------------#

    /** @test */
    public function it_can_add_new_widget()
    {
        $widget = $this->page->addWidget(
            raw('App\Models\Widget')
        );

        $this->assertTrue($this->page->widgets->contains($widget));
    }

    /** @test */
    public function it_check_the_page_widget_exists_state()
    {
        create('App\Models\Widget', ['page_id' => $this->page->id]);
        $anotherPage = create(Page::class);

        $this->assertTrue($this->page->hasWidget());
        $this->assertFalse($anotherPage->hasWidget());
    }

    /** @test */
    public function it_can_add_new_context()
    {
        $context = $this->page->addContext(
            raw('App\Models\Context')
        );

        $this->assertTrue($this->page->contexts->contains($context));
    }

    /** @test */
    public function it_check_the_page_context_exists_state()
    {
        create('App\Models\Context', ['page_id' => $this->page->id]);
        $anotherPage = create(Page::class);

        $this->assertTrue($this->page->hasContext());
        $this->assertFalse($anotherPage->hasContext());
    }
    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Mutator">   ##----------------------------------------------------#

    /** @test */
    public function it_should_slugged_from_the_slug_of_page()
    {
        $page = create(Page::class, [
            'slug' => 'Page-Number-1'
        ]);

        $this->assertEquals(
            $page->slug, 'page-number-1'
        );
    }

    # </editor-fold>
}
