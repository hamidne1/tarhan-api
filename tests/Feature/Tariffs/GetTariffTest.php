<?php

namespace Tests\Feature\Tariffs;

use App\Models\Tariff;
use App\Models\TariffOption;
use Tests\TestCase;

class GetTariffTest extends TestCase {
    /** @test */
    public function it_see_tariff_in_route_tariffs_index()
    {
        $tariff = create(Tariff::class);
        $this->getJson(route('tariffs.index'))
            ->assertStatus(200)
            ->assertSee($tariff->title);
    }

    /** @test */
    public function it_see_tariff_in_route_tariffs_index_in_this_format()
    {
        create(Tariff::class);

        $this->getJson(route('tariffs.index'))
            ->assertJsonStructure(
                [
                    'data' => [
                        [
                            'id', 'title', 'sub_title', 'category_id', 'icon', 'price', 'discount'
                        ]
                    ]
                ]
            );
    }

    /** @test */
    public function it_can_see_tariff_with_options_in_index_of_tariff_method()
    {
        $tariff = create(Tariff::class);

        $option = create(TariffOption::class, [
            'tariff_id' => $tariff->id
        ]);


        $this->getJson(route('tariffs.index', ['with' => 'options']))
            ->assertStatus(200)
            ->assertSee($tariff->title)
            ->assertSee($option->title);
    }
}
