<?php

namespace Tests\Feature\TariffOptions;

use App\Models\Tariff;
use App\Models\TariffOption;
use Illuminate\Support\Arr;
use Tests\TestCase;

class EditTariffOptionTest extends TestCase {
    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return EditTariffOptionTest
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
     * @param null $tariffOptionId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function update($tariffId = null, $tariffOptionId = null)
    {
        $tariffId = $tariffId ?: create(Tariff::class)->id;
        $tariffOptionId = $tariffOptionId ?: create(TariffOption::class, [
            'tariff_id' => $tariffId
        ])->id;

        return $this->adminLogin()->putJson(
            route('tariff.options.update', [$tariffId, $tariffOptionId]), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function an_guest_can_not_update_tariff_option()
    {
        $this->putJson(
            route('tariff.options.update', [1, 1]), []
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_create_new_tariff()
    {
        $this->customerLogin()->putJson(
            route('tariff.options.update', [1, 1]), []
        )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_title_for_tariff_options()
    {
        $this->setData(['title' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function it_required_valid_type()
    {
        $this->setData(['type' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('type');

        $this->setData(['type' => 'non of other enums'])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('type');
    }

    /** @test */
    public function it_can_take_the_valid_icon_for_tariff()
    {
        $this->setData(['icon' => null])
            ->update()
            ->assertJsonMissingValidationErrors('icon');
    }

    # </editor-fold>

    /** @test */
    public function it_update_tariff_has_been_created()
    {
        $this->setData()
            ->update()
            ->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertDatabaseHas('tariff_options', Arr::except(
            $this->data, 'tariff_id'
        ));
    }
}
