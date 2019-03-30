<?php

namespace App\Http\Controllers;

use App\Http\Resources\TariffOptionResource;
use App\Models\Tariff;
use Illuminate\Http\Request;

class TariffOptionsController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @param Tariff $tariff
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Tariff $tariff)
    {
        return TariffOptionResource::collection(
            $tariff->options
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
