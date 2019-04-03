<?php

namespace App\Http\Controllers;

use App\Http\Resources\TariffResource;
use App\Models\Tariff;
use App\Services\TariffService;
use Illuminate\Http\Request;

class TariffsController extends Controller {

    /**
     * TariffsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin')->except('index', 'show');
    }

    /**
     * Display a listing of the resource.
     *
     * @param TariffService $service
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(TariffService $service)
    {
        return TariffResource::collection(
            $service->get()
        );
    }

    /**
     * Display specific of the resource
     *
     * @param Tariff $tariff
     * @return TariffResource
     */
    public function show(Tariff $tariff)
    {
        return new TariffResource($tariff);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'title' => 'required',
            'sub_title' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required',
            'discount' => 'nullable|numeric',
            'icon' => 'nullable'
        ]);

        $tariff = Tariff::create($validated);

        return $this->respondCreated(
            'یک تعرفه جدید ایجاد شد', $tariff
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $validated = $this->validate($request, [
            'title' => 'required',
            'sub_title' => 'required',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required',
            'discount' => 'nullable|numeric',
            'icon' => 'nullable'
        ]);

        Tariff::findOrFail($id)->update($validated);

        return $this->respond(
            'بروزرسانی انجام شد'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        Tariff::findOrFail($id)->delete();

        return $this->respondDeleted();
    }
}
