<?php

namespace Tests\Unit\Models;

use App\Models\Tariff;
use App\Models\TariffOption;
use Tests\TestCase;

class TariffOptionTest extends TestCase {

    #-------------------------------------##   <editor-fold desc="setUp">   ##----------------------------------------------------#

    /**
     * @var TariffOption $tariffOption
     */
    protected $tariffOption;

    /**
     * @var Tariff $tariff
     */
    protected $tariff;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->tariff = create(Tariff::class);
        $this->tariffOption = create(TariffOption::class, [
            'tariff_id' => $this->tariff->id
        ]);
    }

    /** @test */
    public function it_should_extends_base_model()
    {
        $this->assertTrue(
            is_subclass_of(
                $this->tariffOption, 'App\Models\Model'
            )
        );
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The Guarded">   ##----------------------------------------------------#

    /**
     * checking guard data
     *
     * @param array $guardData
     */
    protected function assertGuard(array $guardData)
    {
        $this->tariffOption->update(
            raw(TariffOption::class, $guardData)
        );
        $this->assertDatabaseMissing('tariff_options', $guardData);
    }

    /** @test */
    public function it_should_guarded_the_id_field()
    {
        $this->assertGuard(['id' => 14048343]);
    }

    /** @test */
    public function it_should_guarded_the_tariff_id_field()
    {
        $this->assertGuard(['tariff_id' => 14048343]);
    }

    # </editor-fold>

    #-------------------------------------##   <editor-fold desc="The RelationShips">   ##----------------------------------------------------#

    /** @test */
    public function it_belongs_to_a_tariff()
    {
        $this->assertEquals(
            $this->tariffOption->tariff->id, $this->tariff->id
        );
    }

    # </editor-fold>
}
