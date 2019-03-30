<?php

namespace Tests\Feature\TariffOptions;

use App\Models\Tariff;
use App\Models\TariffOption;
use Tests\TestCase;

class GetTariffOptionTest extends TestCase {
    /** @test */
    public function it_see_tariff_option_in_route_tariff_option_index()
    {
        $tariff = create(Tariff::class);
        $option = create(TariffOption::class, [
            'tariff_id' => $tariff->id
        ]);
        $anotherOption = create(TariffOption::class);

        $this->getJson(route('tariff.options.index', $tariff->id))
            ->assertStatus(200)
            ->assertSee($option->title)
            ->assertDontSee($anotherOption->title);
    }

    /** @test */
    public function it_see_tariff_option_in_route_tariff_option_index_in_this_format()
    {
        $tariff = create(Tariff::class);
        create(TariffOption::class, [
            'tariff_id' => $tariff->id
        ]);
        $this->getJson(route('tariff.options.index', $tariff->id))
            ->assertJsonStructure(
                [
                    'data' => [
                        [
                            'id', 'title', 'tariff_id', 'icon', 'type'
                        ]
                    ]
                ]
            );
    }
}
