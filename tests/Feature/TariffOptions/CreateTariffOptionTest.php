<?php

namespace Tests\Feature\TariffOptions;

use App\Models\Tariff;
use App\Models\TariffOption;
use Tests\TestCase;

class CreateTariffOptionTest extends TestCase {
    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return CreateTariffOptionTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(TariffOption::class, $override);

        return $this;
    }

    /**
     * send the request to store the tariff
     *
     * @param null $tariffId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function store($tariffId = null)
    {
        $tariffId = $tariffId ?: create(Tariff::class)->id;

        return $this->adminLogin()->postJson(
            route('tariffs.options.store', $tariffId), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function an_guest_can_not_create_new_tariff_option()
    {
        $this->postJson(
            route('tariffs.options.store', 1), []
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_create_new_tariff()
    {
        $this->customerLogin()->postJson(
            route('tariffs.options.store', 1), []
        )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_title_for_tariff_options()
    {
        $this->setData(['title' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function it_required_valid_type()
    {
        $this->setData(['type' => null])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('type');

        $this->setData(['type' => 'non of other enums'])
            ->store()
            ->assertStatus(422)
            ->assertJsonValidationErrors('type');
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
        $tariff = create(Tariff::class);

        $this->setData([
            'tariff_id' => $tariff->id
        ])->store($tariff->id)
            ->assertStatus(201)
            ->assertJsonStructure([
                'data', 'message'
            ]);

        $this->assertDatabaseHas('tariff_options', $this->data);
    }
}
