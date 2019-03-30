<?php

namespace Tests\Feature\Tariffs;

use App\Models\Tariff;
use Tests\TestCase;

class CreateTariffTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return CreateTariffTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Tariff::class, $override);

        return $this;
    }

    /**
     * send the request to store the tariff
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function store()
    {
        return $this->adminLogin()->postJson(
            route('tariffs.store'), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function an_guest_can_not_create_new_tariff()
    {
        $this->postJson(
            route('tariffs.store'), []
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_create_new_tariff()
    {
        $this->customerLogin()->postJson(
            route('tariffs.store'), []
        )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_title_for_tariff()
    {
        $this->setData(['title' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function it_required_the_valid_sub_title_for_tariff()
    {
        $this->setData(['sub_title' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('sub_title');
    }

    /** @test */
    public function it_required_valid_category_id()
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
    public function it_required_the_valid_price_for_tariff()
    {
        $this->setData(['price' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('price');
    }

    /** @test */
    public function it_can_take_valid_discount_for_tariff()
    {
        $this->setData(['discount' => 'string'])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('discount');

    }

    /** @test */
    public function it_can_take_the_valid_icon_for_tariff()
    {
        $this->setData(['icon' => null])
            ->store()
            ->assertJsonMissingValidationErrors('icon');
    }

    # </editor-fold>

    /** @test */
    public function it_store_tariff_in_database()
    {
        $this->setData()
            ->store()
            ->assertStatus(201)
            ->assertJsonStructure([
                'data', 'message'
            ]);

        $this->assertDatabaseHas('tariffs', $this->data);
    }


}
