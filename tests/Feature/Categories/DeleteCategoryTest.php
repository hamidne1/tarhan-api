<?php

namespace Tests\Feature\Categories;

use App\Models\Category;
use Tests\TestCase;

class DeleteCategoryTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * send the request to destroy the category
     *
     * @param null $categoryId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function destroy($categoryId = null)
    {
        $categoryId = $categoryId ?: create(Category::class);

        return $this->deleteJson(
            route('categories.destroy', $categoryId)
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_delete_a_category()
    {
        $this->destroy()->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_delete_a_category()
    {
        $this->customerLogin()->destroy()->assertStatus(401);
    }

    # </editor-fold>

    /** @test */
    public function an_authenticated_admin_can_delete_a_category()
    {
        $category = create(Category::class);

        $this->adminLogin()
            ->destroy($category->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('categories', $category->toArray());
    }

}
