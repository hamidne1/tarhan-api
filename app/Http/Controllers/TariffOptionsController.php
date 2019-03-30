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
     * @param Tariff $tariff
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Tariff $tariff)
    {
        $validated = $this->validate($request, [
            'title' => 'required',
            'icon' => 'nullable',
            'type' => [
                'required', \Illuminate\Validation\Rule::in(
                    \App\Enums\OptionTypeEnum::values()
                )
            ],
            'tariff_id' => 'required'
        ]);

        $option = $tariff->options()->create($validated);

        return $this->respondCreated(
            'یک گزینه ی جدید افزوده شد', new TariffOptionResource($option)
        );

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
