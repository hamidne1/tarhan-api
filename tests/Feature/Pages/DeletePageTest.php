<?php

namespace Tests\Feature\Pages;

use App\Models\Page;
use Tests\TestCase;

class DeletePageTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * send the request to destroy the page
     *
     * @param null $pageSlug
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function destroy($pageSlug = null)
    {
        $pageSlug = $pageSlug ?: create(Page::class)->slug;

        return $this->deleteJson(
            route('pages.destroy', $pageSlug)
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_delete_a_page()
    {
        $this->destroy()->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_delete_a_page()
    {
        $this->customerLogin()->destroy()->assertStatus(401);
    }

    # </editor-fold>

    /** @test */
    public function an_authenticated_admin_can_delete_a_page()
    {
        $page = create(Page::class);

        $this->adminLogin()
            ->destroy($page->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('pages', $page->toArray());
    }

    /** @test */
    public function an_authenticated_admin_can_not_delete_a_page_that_have_a_widget()
    {
        $page = create(Page::class);

        create('App\Models\Widget', ['page_id' => $page->id]);

        $this->adminLogin()
            ->destroy($page->id)
            ->assertStatus(200);

        $this->assertDatabaseHas('pages', $page->toArray());
    }
}
