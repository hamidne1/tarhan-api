<?php

namespace App\Http\Controllers;

use App\Http\Resources\TariffOptionResource;
use App\Models\Tariff;
use Illuminate\Http\Request;

class TariffOptionsController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:admin')->except('index');
    }

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
            'tariff_id' => 'required|exists:tariffs,id'
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
     * @param Tariff $tariff
     * @param $tariffOptionId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Tariff $tariff, $tariffOptionId)
    {
        $validated = $this->validate($request, [
            'title' => 'required',
            'icon' => 'nullable',
            'type' => [
                'required', \Illuminate\Validation\Rule::in(
                    \App\Enums\OptionTypeEnum::values()
                )
            ],
        ]);

        $tariff->options()->findOrFail($tariffOptionId)->update($validated);

        return $this->respond(
            'بروزرسانی با موفقیت انجام شد'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Tariff $tariff
     * @param $tariffOptionId
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Tariff $tariff, $tariffOptionId)
    {
        $tariff->options()->findOrFail($tariffOptionId)->delete();

        return $this->respondDeleted();
    }
}
