<?php

namespace Tests\Feature\Tariffs;

use App\Models\Tariff;
use Tests\TestCase;

class EditTariffTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var $data
     */
    protected $data;

    /**
     * set data property
     *
     * @param array $override
     * @return EditTariffTest
     */
    protected function setData($override = [])
    {
        $this->data = raw(Tariff::class, $override);

        return $this;
    }

    /**
     * send the request to store the tariff
     *
     * @param null $tariffId
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function update($tariffId = null)
    {
        $tariffId = $tariffId ?: create(Tariff::class);

        return $this->adminLogin()->putJson(
            route('tariffs.update', $tariffId), $this->data
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Security">   ##----------------------------------------------------#

    /** @test */
    public function an_guest_can_not_edit_category()
    {
        $this->putJson(
            route('tariffs.update', 1), []
        )->assertStatus(401);
    }

    /** @test */
    public function an_authenticated_customer_can_not_edit_category()
    {
        $this->customerLogin()->putJson(
            route('tariffs.update', 1), []
        )->assertStatus(401);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Validation">   ##----------------------------------------------------#

    /** @test */
    public function it_required_the_valid_title_for_tariff()
    {
        $this->setData(['title' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('title');
    }

    /** @test */
    public function it_required_the_valid_sub_title_for_tariff()
    {
        $this->setData(['sub_title' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('sub_title');
    }

    /** @test */
    public function it_nullable_valid_category_id()
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
    public function it_required_the_valid_price_for_tariff()
    {
        $this->setData(['price' => null])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('price');
    }

    /** @test */
    public function it_can_take_valid_discount_for_tariff()
    {
        $this->setData(['discount' => 'string'])
            ->update()
            ->assertStatus(422)
            ->assertJsonValidationErrors('discount');

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
    public function it_update_tariff_in_database()
    {
        $this->setData()
            ->update()
            ->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);

        $this->assertDatabaseHas('tariffs', $this->data);
    }
}
