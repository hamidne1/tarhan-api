<?php

namespace Tests\Feature\Pages;

use App\Models\Widget;
use Tests\TestCase;

class GetPageTest extends TestCase {

    /** @test */
    public function it_see_widget_in_route_widgets_index()
    {
        $widget = create(Widget::class);
        $this->getJson(route('pages.index'))
            ->assertStatus(200)
            ->assertSee($widget->title);
    }

    /** @test */
    public function it_see_widget_in_route_widgets_index_in_this_format()
    {
        create(Widget::class);

        return $this->getJson(route('pages.index'))
            ->assertJsonStructure(
                [
                    'data' => [
                        ['id', 'slug']
                    ]
                ]
            );
    }
}
