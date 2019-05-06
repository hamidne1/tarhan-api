<?php

namespace Tests\Feature\Widgets;

use App\Models\Widget;
use Tests\TestCase;

class CreateWidgetTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return CreateWidgetTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Widget::class, $override);

        return $this;
    }

    /**
     * send the request to store the widget
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function store()
    {
        return $this->adminLogin()->postJson(
            route('widgets.store'), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_create_new_widget()
    {
        $this->setData()
            ->postJson(
                route('widgets.store'), []
            )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_create_new_widget()
    {
        $this->customerLogin()->setData()
            ->postJson(
                route('widgets.store'), []
            )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_page_for_widget()
    {
        $this->setData(['page_id' => null])
            ->store()
            ->assertJsonMissingValidationErrors('page_id');

        $this->setData(['page_id' => 999])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('page_id');
    }

    /** @test */
    public function it_can_take_the_valid_category_id_for_widget()
    {
        $this->setData(['category_id' => null])
            ->store()
            ->assertJsonMissingValidationErrors('category_id');

        $this->setData(['category_id' => 999])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('category_id');

    }

    /** @test */
    public function it_required_the_col_for_widget()
    {
        $this->setData(['col' => null])
            ->store()
            ->assertStatus(201)
            ->assertJsonMissingValidationErrors('col');
    }


    /** @test */
    public function it_required_the_valid_slug_for_widget()
    {
        $this->setData(['slug' => null])
            ->store()
            ->assertStatus(201)
            ->assertJsonMissingValidationErrors('slug');
    }

    /** @test */
    public function it_required_the_valid_group_for_widget()
    {
        $this->setData(['slug' => null,'group' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors(['group' , 'slug']);

    }

    /** @test */
    public function it_required_the_valid_alt_for_widget()
    {
        $this->setData(['alt' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('alt');
    }

    /** @test */
    public function it_required_the_valid_href_for_widget()
    {
        $this->setData(['href' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('href');

        $this->setData(['href' => 'string not url'])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('href');
    }

    /** @test */
    public function it_required_the_valid_src_for_widget()
    {
        $this->setData(['src' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('src');
    }

    # </editor-fold>


    /** @test */
    public function it_store_new_widget_to_database()
    {
        $this->setData()
            ->store()
            ->assertStatus(201)
            ->assertJsonStructure([
                'message', 'data'
            ]);

        $this->assertDatabaseHas('widgets', $this->data);
    }


}
