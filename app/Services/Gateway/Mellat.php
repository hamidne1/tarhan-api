<?php

namespace App\Services\Gateway;

use App\Exceptions\BankException;
use App\Models\Transaction;
use Illuminate\Support\Facades\Config;

class Mellat extends BankAbstract {

    /**
     * the wsdl url (address of SOAP server)
     *
     */
    const WSDL = 'https://bpm.shaparak.ir/pgwchannel/services/pgw?wsdl';

    /**
     * Send a request to pay
     *
     * @throws \SoapFault
     * @throws MellatException
     * @throws \Exception
     */
    public function sendRequest()
    {
        if (!$this->receiptId instanceof \App\Models\Receipt)
            throw new \Exception('Set Receipt Model!');

        $this->initializeTransaction(\App\Enums\GateWay\TransactionPortEnum::Mellat);

        $parameters = Config::get('gateway.mellat') + [
                'orderId' => $this->transactionId, // Must unique for every request bpPayRequest method
                'amount' => $this->getAmount(),
                'localDate' => date('Ymd'),
                'localTime' => date('His'),
                'additionalData' => '' // 1000 character that save for every transaction
            ];

        try {
            $client = new \SoapClient(self::WSDL);
            $response = $client->bpPayRequest($parameters);
        } catch (\SoapFault $e) {
            $this->failedTransaction(500, $e->getMessage());
            throw $e;
        }

        $responseArr = explode(',', $response->return); // "0, AF82041a2Bf6989c7fF9" => "ResCode, RefId"

        if ($responseArr[0] != '0') {
            $this->failedTransaction($responseArr[0], MellatException::$errorData[$responseArr[0]] ?? null);
            throw new MellatException($responseArr[0]);
        }

        $this->updateTransactionRefId($responseArr[1]);
        return view('mellat', compact('refId'));

    }

    /**
     * verify the response of bank
     *
     * @return boolean
     * @throws BankException
     * @throws \SoapFault
     */
    public function verify()
    {
        if (!$this->request->has('transaction_id') && !$this->request->has('iN')) {
            throw new BankException('اطلاعات بازگشتی از بانک صحیح نمی باشد', 104);
        }

        $transactionId = $this->request->get(
            'transaction_id', $this->request->get('iN')
        );

        $transaction = $this->transaction = Transaction::findOrFail($transactionId);
        $this->transactionId = $transactionId;

        if ($transaction->isRetry())
            throw new BankException('نتیجه تراکنش قبلا از طرف بانک اعلام گردیده است.', 101);

        $this->transactionId = $transaction->id;
        $this->amount = $transaction->amount;
        $this->refId = $transaction->ref_id;
        if ($this->refId != $this->request->get('RefId'))
            throw new BankException('مشکلی در کد ارجاع بانک وجود دارد');

        $this->trackingCode = $this->request->get('SaleReferenceId');
        $this->cardNumber = $this->request->get('CardHolderPan');

        if ($resCode = $this->request->get('ResCode') != '0') {
            $this->failedTransaction($resCode, MellatException::$errorData[$resCode] . "#{$resCode}" ?? null);
            throw new MellatException($resCode);
        }
        // ResCode === '0': mellat gateway is successfully in mellat side, we can start validate transaction by method bpVerifyRequest
        $parameters = Config::get('gateway.mellat') + [
                'orderId' => $this->transactionId(),
                'saleOrderId' => $this->transactionId(), // THe Order Id created before step
                'saleReferenceId' => $this->trackingCode
            ];
        try {
            $client = new \SoapClient(self::WSDL);
            $resCode = $client->bpVerifyRequest($parameters);
        } catch (\SoapFault $exception) {
            $this->failedTransaction(500, $exception->getMessage());
            throw $exception;
        }

        if ($resCode != '0') {
            $this->failedTransaction($resCode, MellatException::$errorData[$resCode] . "#{$resCode}" ?? null);
            throw new MellatException($resCode);
        }

        // Request to pay me the customer amount that has in mellat pocket
        $parameters = Config::get('gateway.mellat') + [
                'orderId' => $this->transactionId(),
                'saleOrderId' => $this->transactionId(), // THe Order Id created before step
                'saleReferenceId' => $this->trackingCode
            ];

        try {
            $client = new \SoapClient(self::WSDL);
            $response = $client->bpSettleRequest($parameters);

        } catch (\SoapFault $exception) {
            $this->failedTransaction(500, $exception->getMessage());
            throw $exception;
        }

        if (!($response->return == '0' || $response->return == '45')) {
            $this->failedTransaction($response->return, MellatException::$errorData[$response->return] . "#{$response->return}" ?? null);
            throw new MellatException($response->return);
        }

        $this->succeedTransaction();
        return true;
    }

}