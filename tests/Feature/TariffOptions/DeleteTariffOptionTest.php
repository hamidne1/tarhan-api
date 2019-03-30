<?php

namespace Tests\Feature\TariffOptions;

use App\Models\Tariff;
use App\Models\TariffOption;
use Tests\TestCase;

class DeleteTariffOptionTest extends TestCase {
    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * send the request to destroy the tariffOption
     *
     * @param null $tariffId
     * @param null $tariffOptionId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function destroy($tariffId = null, $tariffOptionId = null)
    {
        $tariffId = $tariffId ?: create(Tariff::class)->id;
        $tariffOptionId = $tariffOptionId ?: create(TariffOption::class, [
            'tariff_id' => $tariffId
        ])->id;

        return $this->deleteJson(
            route('tariff.options.destroy', [$tariffId, $tariffOptionId])
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function guest_can_not_delete_a_tariff()
    {
        $this->destroy()->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_delete_a_tariff()
    {
        $this->customerLogin()->destroy()->assertStatus(401);
    }

    # </editor-fold>

    /** @test */
    public function an_authenticated_admin_can_delete_a_tariff()
    {
        $tariff = create(Tariff::class);
        $tariffOption = create(TariffOption::class, [
            'tariff_id' => $tariff->id
        ]);

        $this->adminLogin()
            ->destroy($tariff->id, $tariffOption->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('tariff_options', $tariffOption->toArray());
    }
}
