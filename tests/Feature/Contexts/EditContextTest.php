<?php

namespace Tests\Feature\Contexts;

use App\Models\Context;
use Tests\TestCase;

class EditContextTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return EditContextTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Context::class, $override);

        return $this;
    }

    /**
     * send the request to update the context
     *
     * @param null $contextId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function update($contextId = null)
    {
        $contextId = $contextId ?: create(Context::class)->id;

        return $this->adminLogin()->putJson(
            route('contexts.update', $contextId), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_edit_context()
    {
        $this->putJson(
            route('contexts.update', create(Context::class)->id), []
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_edit_context()
    {
        $this->customerLogin()->setData()
            ->putJson(
                route('contexts.update', create(Context::class)->id), []
            )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_page_for_context()
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
    public function it_required_the_valid_slug_for_context()
    {
        $this->setData(['slug' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('slug');

        $this->setData(['slug' => create(Context::class)->slug])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('slug');
    }

    /** @test */
    public function it_can_take_the_valid_parent_id_for_context()
    {
        $this->setData(['parent_id' => null])
            ->update()
            ->assertJsonMissingValidationErrors('parent_id');

        $this->setData(['parent_id' => 999])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('parent_id');

    }

    /** @test */
    public function it_required_the_icon_for_context()
    {
        $this->setData(['icon' => null])
            ->update()
            ->assertJsonMissingValidationErrors('icon');
    }

    /** @test */
    public function it_required_the_valid_href_for_context()
    {
        $this->setData(['href' => null])
            ->update()
            ->assertJsonMissingValidationErrors('href');

        $this->setData(['href' => 'string'])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('href');
    }

    /** @test */
    public function it_required_the_valid_value_for_context()
    {
        $this->setData(['value' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('value');
    }

    # </editor-fold>


    /** @test */
    public function it_update_context_to_database()
    {
        $this->setData()->update()
            ->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertDatabaseHas('contexts', [
            'slug' => $this->data['slug']
        ]);
    }
}
