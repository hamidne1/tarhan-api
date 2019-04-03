<?php

namespace Tests\Feature\Portfolios;

use App\Models\Multimedia;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetPortfolioTest extends TestCase
{
    /** @test */
    public function it_see_portfolios_in_route_portfolios_index(){
        $this->withoutExceptionHandling();
        $multimedia = create(Multimedia::class);
        $this->adminLogin()->getJson(route('portfolio.index'))
            ->assertStatus(200)
            ->assertSee($multimedia->portfolio()->title);
            //->assertSee($multimedia->portfolio()->path);
    }
}
