<?php

namespace App\Http\Controllers;

use App\Models\Tariff;
use App\Services\Gateway\Mellat;
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
     * @param Mellat $mellat
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, ReceiptService $receiptService)
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

        $receipt = $receiptService->store($order);


    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
