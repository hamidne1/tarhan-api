<?php

namespace Tests\Feature\Portfolios;

use App\Models\Portfolio;
use Tests\TestCase;

class CreatePortfolioTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;


    /**
     * set data property
     *
     * @param array $override
     * @return CreatePortfolioTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Portfolio::class, $override);

        return $this;
    }

    /**
     * send the request to store the portfolio
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function store()
    {
        return $this->adminLogin()->postJson(
            route('portfolios.store'), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_create_new_portfolio()
    {
        $this->setData()
            ->postJson(
                route('portfolios.store'), []
            )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_create_new_portfolio()
    {
        $this->customerLogin()->setData()
            ->postJson(
                route('portfolios.store'), []
            )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_category_id_for_portfolio()
    {
        $this->setData(['category_id' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('category_id');

        $this->setData(['category_id' => 999])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('category_id');
    }

    /** @test */
    public function it_required_the_valid_title_for_portfolio()
    {
        $this->setData(['title' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function it_can_take_the_description_for_portfolio()
    {
        $this->setData(['description' => null])
            ->store()
            ->assertJsonMissingValidationErrors('description');
    }

    /** @test */
    public function it_can_take_the_valid_link_for_portfolio()
    {
        $this->setData(['link' => null])
            ->store()
            ->assertJsonMissingValidationErrors('link');

        $this->setData(['link' => 'string'])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('link');
    }

    # </editor-fold>


    /** @test */
    public function it_store_new_portfolio_to_database()
    {
//        $this->withoutExceptionHandling();
        $this->setData()
            ->store()
            ->assertStatus(201)
            ->assertJsonStructure([
                'message', 'data'
            ]);

        $this->assertDatabaseHas('portfolios', $this->data);
    }
}
