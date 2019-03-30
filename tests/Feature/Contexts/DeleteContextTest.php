<?php

namespace Tests\Feature\Widgets;

use App\Models\Context;
use Tests\TestCase;

class DeleteContextTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * send the request to destroy the context
     *
     * @param null $contextId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function destroy($contextId = null)
    {
        $contextId = $contextId ?: create(Context::class);

        return $this->deleteJson(
            route('contexts.destroy', $contextId)
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_delete_a_context()
    {
        $this->destroy()->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_delete_a_context()
    {
        $this->customerLogin()->destroy()->assertStatus(401);
    }

    # </editor-fold>

    /** @test */
    public function an_authenticated_admin_can_delete_a_context()
    {
        $context = create(Context::class);

        $this->adminLogin()
            ->destroy($context->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('contexts', $context->toArray());
    }
}
