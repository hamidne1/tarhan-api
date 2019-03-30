<?php

namespace Tests\Feature\Widgets;

use App\Models\Widget;
use Tests\TestCase;

class DeleteWidgetTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * send the request to destroy the widget
     *
     * @param null $widgetId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function destroy($widgetId = null)
    {
        $widgetId = $widgetId ?: create(Widget::class);

        return $this->deleteJson(
            route('widgets.destroy', $widgetId)
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_delete_a_widget()
    {
        $this->destroy()->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_delete_a_widget()
    {
        $this->customerLogin()->destroy()->assertStatus(401);
    }

    # </editor-fold>

    /** @test */
    public function an_authenticated_admin_can_delete_a_widget()
    {
        $widget = create(Widget::class);

        $this->adminLogin()
            ->destroy($widget->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('widgets', $widget->toArray());
    }
}
