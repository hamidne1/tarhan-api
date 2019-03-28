<?php

namespace Tests\Feature\Tariffs;

use App\Models\Tariff;
use Tests\TestCase;

class DeleteTariffTest extends TestCase {
    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * send the request to destroy the tariff
     *
     * @param null $tariffId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function destroy($tariffId = null)
    {
        $tariffId = $tariffId ?: create(Tariff::class)->id;

        return $this->deleteJson(
            route('tariffs.destroy', $tariffId)
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

        $this->adminLogin()
            ->destroy($tariff->id)
            ->assertStatus(204);

        $this->assertDatabaseMissing('tariffs', $tariff->toArray());
    }
}
