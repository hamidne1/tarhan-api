<?php

namespace Tests\Feature\Portfolios;

use App\Models\Portfolio;
use Tests\TestCase;

class DeletePortfolioTest extends TestCase {
    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * send the request to destroy the portfolio
     *
     * @param null $portfolioId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function destroy($portfolioId = null)
    {
        $portfolioId = $portfolioId ?: create(Portfolio::class);

        return $this->deleteJson(
            route('portfolios.destroy', $portfolioId)
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_delete_a_portfolio()
    {
        $this->destroy()->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_delete_a_portfolio()
    {
        $this->customerLogin()->destroy()->assertStatus(401);
    }

    # </editor-fold>

    /** @test */
    public function an_authenticated_admin_can_delete_a_portfolio()
    {
        $portfolio = create(Portfolio::class);

        $this->adminLogin()
            ->destroy($portfolio->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('portfolios', $portfolio->toArray());
    }
}
