<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReceiptResource;
use App\Models\Order;
use App\Services\ReceiptService;
use Illuminate\Http\Request;

class OrderReceiptsController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Order $order
     * @param ReceiptService $service
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Order $order, ReceiptService $service)
    {
        $validated = $this->validate($request, [
            'price' => 'required|numeric'
        ]);

        $receipt = $service->store($order, $validated['price']);

        return $this->respondCreated(
            'یک صورت حساب جدید ایجاد شد', new ReceiptResource($receipt)
        );

    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Order $order, $id)
    {
        $validated = $this->validate($request, [
            'price' => 'required|numeric'
        ]);

        $order->receipts()->findOrFail($id)->update($validated);

        return $this->respond('بروزرسانی انجام شد');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Order $order
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Order $order, $id)
    {
        $order->receipts()->findOrFail($id)->delete();

        return $this->respondDeleted();
    }
}
