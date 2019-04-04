<?php

namespace App\Http\Controllers;

use App\Exceptions\BankException;
use App\Services\PaymentService;

class VerifyController extends Controller {

    /**
     * @var PaymentService
     */
    protected $service;

    /**
     * VerifyController constructor.
     *
     * @param PaymentService $service
     */
    public function __construct(PaymentService $service)
    {
        $this->service = $service;
    }

    /**
     * verify the bank payment
     *
     * @return \App\Models\Transaction|\Illuminate\Http\JsonResponse
     */
    public function verify()
    {
        try {
            return $this->service->verifyPay();
        } catch (BankException $e) {
            return $this->respondInternalError($e->getMessage());
        } catch (\SoapFault $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }
}
