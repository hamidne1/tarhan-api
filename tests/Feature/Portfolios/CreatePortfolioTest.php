<?php

namespace Tests\Feature\Portfolios;

use App\Models\Category;
use App\Models\Field;
use App\Models\Multimedia;
use App\Models\Portfolio;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreatePortfolioTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @param null $path
     * @return CreatePortfolioTest
     */
    protected function setData($override = [], $path = '')
    {

        $this->data = raw(Portfolio::class, $override);
        $field = raw(Field::class);
        Category::findOrFail($this->data['category_id'])->addFields($field);
        $this->data = array_merge($this->data, ['path' => $path]);
        return $this;
    }

    /**
     * send the request to store the category
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function store()
    {
        return $this->adminLogin()->postJson(
            route('portfolio.store'), $this->data
        );
    }

    /** @test */
    public function an_guest_can_not_create_new_portfolio()
    {
        $this->postJson(
            route('portfolio.store'), []
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_create_new_portfolio()
    {
        $this->customerLogin()->postJson(
            route('portfolio.store'), []
        )->assertStatus(401);
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
    public function it_required_the_valid_icon_for_portfolio()
    {
        $this->setData(['description' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('description');
    }

    /** @test */
    public function it_nullable_valid_category_id()
    {
        $this->setData();
        $this->data['category_id'] = null;
        $this->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('category_id');

        $this->setData(['category_id' => 999]);
        $this->data['category_id'] = null;
        $this->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('category_id');
    }

    /** @test */
    public function it_store_portfolio_in_database()
    {
        $this->withoutExceptionHandling();
        $this->setData()
           ->store();

    }
}
