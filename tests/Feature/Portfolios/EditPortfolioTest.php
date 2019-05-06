<?php

namespace Tests\Feature\Portfolios;

use App\Models\Portfolio;
use Tests\TestCase;

class EditPortfolioTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;


    /**
     * set data property
     *
     * @param array $override
     * @return EditPortfolioTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Portfolio::class, $override);

        return $this;
    }

    /**
     * send the request to update the portfolio
     *
     * @param null $portfolioId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function update($portfolioId = null)
    {
        $portfolioId = $portfolioId ?: create(Portfolio::class)->id;

        return $this->adminLogin()->putJson(
            route('portfolios.update', $portfolioId), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_create_update_portfolio()
    {
        $this->setData()
            ->putJson(
                route('portfolios.update', 1), []
            )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_update_portfolio()
    {
        $this->customerLogin()->setData()
            ->putJson(
                route('portfolios.update', 1), []
            )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_category_id_for_portfolio()
    {
        $this->setData(['category_id' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('category_id');

        $this->setData(['category_id' => 999])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('category_id');
    }

    /** @test */
    public function it_required_the_valid_title_for_portfolio()
    {
        $this->setData(['title' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function it_can_take_the_description_for_portfolio()
    {
        $this->setData(['description' => null])
            ->update()
            ->assertJsonMissingValidationErrors('description');

    }

    /** @test */
    public function it_can_take_the_valid_link_for_portfolio()
    {
        $this->setData(['link' => null])
            ->update()
            ->assertJsonMissingValidationErrors('link');

        $this->setData(['link' => 'string'])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('link');
    }

    /** @test */
    public function it_can_take_the_valid_src_for_portfolio()
    {
        $this->setData(['src' => null])
            ->update()
            ->assertJsonMissingValidationErrors('src');

        $this->setData(['src' => 'string'])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('src');
    }

    # </editor-fold>


    /** @test */
    public function it_update_new_portfolio_to_database()
    {
        $this->setData()
            ->update()
            ->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertDatabaseHas('portfolios', $this->data);
    }
}
