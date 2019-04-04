<?php

namespace Tests\Feature\Portfolios;

use App\Models\Portfolio;
use Tests\TestCase;

class GetPortfolioTest extends TestCase {
    /** @test */
    public function it_see_portfolio_in_route_portfolios_index()
    {
        $portfolio = create(Portfolio::class);
        $this->getJson(route('portfolios.index'))
            ->assertStatus(200)
            ->assertSee($portfolio->title);
    }

    /** @test */
    public function it_see_portfolio_in_route_portfolios_index_in_this_format()
    {
        create(Portfolio::class);

        $this->getJson(route('portfolios.index'))
            ->assertJsonStructure(
                [
                    'data' => [
                        [
                            'id', 'title', 'link', 'description', 'category_id'
                        ]
                    ]
                ]
            );
    }


}
