<?php

namespace App\Services;

use App\Exceptions\BankException;
use App\Models\Receipt;
use App\Services\Gateway\Mellat;
use App\Services\Gateway\MellatException;
use Illuminate\Support\Facades\Log;

class PaymentService {

    /**
     * @var Mellat
     */
    protected $mellat;

    /**
     * PaymentsController constructor.
     *
     * @param Mellat $mellat
     */
    public function __construct(Mellat $mellat)
    {
        $this->mellat = $mellat;
    }

    /**
     * connect to bank request sending
     *
     * @param Receipt $receipt
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws MellatException
     * @throws \SoapFault
     * @throws \Exception
     */
    public function requestToPay(Receipt $receipt)
    {
        try {
            return $this->mellat
                ->setAmount($receipt->price)
                ->setReceiptId($receipt->id)
                ->sendRequest();

        } catch (MellatException $e) {
            Log::critical('Error reporter for Bank: ' . $e->getMessage());
            throw $e;
        } catch (\SoapFault $e) {
            Log::critical('Error reporter for Soap: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * verify the bank payment
     *
     * @return \App\Models\Transaction
     * @throws BankException
     * @throws \SoapFault
     */
    public function verifyPay()
    {
        try {
            $this->mellat->verify();

            $this->mellat->getTransaction()->receipt()->update([
                'status' => \App\Enums\ReceiptStatusEnum::Paid
            ]);

            return $this->mellat->getTransaction();
        } catch (BankException $e) {
            Log::info('Bank: ' . $e->getMessage());
            throw $e;
        } catch (\SoapFault $e) {
            Log::info('Soap Error: ' . $e->getMessage());
            throw $e;
        }
    }
}