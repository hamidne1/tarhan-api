<?php


namespace App\Services;


use App\Filters\TariffFilter;
use App\Models\Tariff;

class TariffService {

    #-------------------------------------##   <editor-fold desc="inject filter and config service">   ##----------------------------------------------------#

    /**
     * @var TariffFilter $filter
     */
    protected $filter;

    /**
     * AttributeService constructor.
     *
     * @param TariffFilter $filter
     */
    public function __construct(TariffFilter $filter)
    {
        $this->filter = $filter;
    }

    # </editor-fold>

    public function get()
    {
        return Tariff::filter($this->filter)->get();
    }


    public function show($id)
    {
        return Tariff::filter($this->filter)->findOrFail($id);
    }


}