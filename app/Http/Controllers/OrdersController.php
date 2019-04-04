<?php

namespace App\Http\Controllers;

use App\Models\Tariff;
use App\Services\Gateway\MellatException;
use App\Services\PaymentService;
use App\Services\ReceiptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrdersController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:customer');
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
     * @param ReceiptService $receiptService
     * @param PaymentService $paymentService
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, ReceiptService $receiptService, PaymentService $paymentService)
    {
        $validated = $this->validate($request, [
            'tariff_id' => 'required|exists:tariffs,id',
            'description' => 'nullable'
        ]);

        $tariff = Tariff::findOrFail($validated['tariff_id']);

        $order = Auth::guard('customer')
            ->user()
            ->orders()
            ->create([
                'title' => $tariff->full_title,
                'description' => $validated['description'],
                'price' => $tariff->payment
            ]);

        try {
            return $paymentService->requestToPay(
                $receiptService->store($order, ceil($order->price * 0.5))
            );
        } catch (MellatException $e) {
            return $this->respondInternalError($e->getMessage());
        } catch (\SoapFault $e) {
            return $this->respondInternalError('در اتصال به بانک مشکلی بوجود آمده است.');
        } catch (\Exception $e) {
            return $this->respondInternalError('صورت حسابی یافت نشد');
        }
    }
}
