<?php

namespace Tests\Unit\Models;

use App\Models\Widget;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WidgetTest extends TestCase {
    use  WithFaker;

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var Widget $widget
     */
    protected $widget;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->widget = create(Widget::class);
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
        $this->widget->update(
            raw(Widget::class, $guardData)
        );
        $this->assertDatabaseMissing('widgets', $guardData);
    }

    /** @test */
    public function it_should_guarded_the_id_field()
    {
        $this->assertGuard(['id' => 14048343]);
    }


    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    # </editor-fold>

    /** @test */
    public function it_belongs_to_a_page()
    {

        $page = create('App\Models\Page');
        $widget = create(Widget::class, ['page_id' => $page->id]);

        $this->assertEquals($page->toArray(), $widget->page->toArray());
    }
}
