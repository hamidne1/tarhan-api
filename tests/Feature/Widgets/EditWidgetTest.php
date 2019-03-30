<?php

namespace Tests\Feature\Widgets;

use App\Models\Widget;
use Tests\TestCase;

class EditWidgetTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return EditWidgetTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Widget::class, $override);

        return $this;
    }

    /**
     * send the request to update the widget
     *
     * @param null $widgetId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function update($widgetId = null)
    {
        $widgetId = $widgetId ?: create(Widget::class);

        return $this->adminLogin()->putJson(
            route('widgets.update', $widgetId), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_edit_widget()
    {
        $this->putJson(
            route('widgets.update', create(Widget::class)->id), []
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_edit_widget()
    {
        $this->customerLogin()->setData()
            ->putJson(
                route('widgets.update', create(Widget::class)->id), []
            )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_page_for_widget()
    {
        $this->setData(['page_id' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('page_id');

        $this->setData(['page_id' => 999])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('page_id');
    }

    /** @test */
    public function it_can_take_the_valid_category_id_for_widget()
    {
        $this->setData(['category_id' => null])
            ->update()
            ->assertJsonMissingValidationErrors('category_id');

        $this->setData(['category_id' => 999])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('category_id');

    }

    /** @test */
    public function it_required_the_col_for_widget()
    {
        $this->setData(['col' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('col');
    }

    /** @test */
    public function it_required_the_valid_group_for_widget()
    {
        $this->setData(['group' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('group');

        $this->setData(['group' => 'non of enums'])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('group');
    }

    /** @test */
    public function it_required_the_valid_title_for_widget()
    {
        $this->setData(['title' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function it_required_the_valid_href_for_widget()
    {
        $this->setData(['href' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('href');

        $this->setData(['href' => 'string not url'])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('href');
    }

    /** @test */
    public function it_required_the_valid_src_for_widget()
    {
        $this->setData(['src' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('src');
    }

    # </editor-fold>


    /** @test */
    public function it_update_widget_to_database()
    {
        $this->setData()->update()
            ->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertDatabaseHas('widgets', $this->data);
    }
}
