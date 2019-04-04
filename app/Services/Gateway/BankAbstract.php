<?php

namespace App\Services\Gateway;

use App\Models\Transaction;
use Illuminate\Http\Request;

abstract class BankAbstract {

    /**
     * @var Request
     */
    protected $request;

    /**
     * card number of transaction
     *
     * @var string $cardNumber
     */
    protected $cardNumber;

    /**
     * amount of payment Rials
     *
     * @var integer $refId
     */
    protected $refId;

    /**
     * tracking code of transaction
     *
     * @var string $trackingCode
     */
    protected $trackingCode;

    /**
     * transaction id
     *
     * @var integer $transactionId
     */
    protected $transactionId;

    /**
     *  transaction object
     *
     * @var Transaction $transaction
     */
    protected $transaction;

    /**
     * $receiptId
     *
     * @var integer $receiptId
     */
    protected $receiptId;

    /**
     * amount of payment Rials
     *
     * @var integer $amount
     */
    protected $amount;

    /**
     * BankAbstract constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * amount setter
     *
     * @param mixed $amount
     * @return BankAbstract|Mellat
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * receiptId setter
     *
     * @param integer $receiptId
     * @return BankAbstract|Mellat
     */
    public function setReceiptId($receiptId)
    {
        $this->receiptId = $receiptId;

        return $this;
    }

    /**
     * amount getter
     *
     * @return int
     */
    public function getAmount()
    {
        return $this->amount;
    }


    /**
     * card number getter
     *
     * @return string
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * ref id getter
     *
     * @return string
     */
    public function getRefId()
    {
        return $this->refId;
    }

    /**
     * tracking code getter
     *
     * @return string
     */
    public function getTrackingCode()
    {
        return $this->refId;
    }

    /**
     * order getter
     *
     * @return Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * order getter
     *
     * @return int
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * initialize transaction
     *
     * @param $port
     */
    protected function initializeTransaction($port)
    {
        $transaction = Transaction::create([
            'port' => $port,
            'price' => $this->getAmount(),
            'ip' => \Illuminate\Support\Facades\Request::ip(),
            'receipt_id' => $this->receiptId
        ]);

        $this->transactionId = $transaction->id;
    }

    /**
     * update ref id of transaction
     *
     * @param $refId
     * @return bool
     */
    protected function updateTransactionRefId($refId)
    {
        return Transaction::whereId($this->getTransactionId())
            ->update([
                'ref_id' => $this->refId = $refId,
            ]);
    }

    /**
     * set transaction to success state
     *
     * @param int $result_code
     * @param null $result_message
     * @return bool
     */
    protected function succeedTransaction($result_code = 0, $result_message = null)
    {
        return Transaction::whereId($this->getTransactionId())
            ->update([
                'tracking_code' => $this->getTrackingCode(),
                'cart_number' => $this->getCardNumber(),
                'paid_at' => \Illuminate\Support\Carbon::now(),
                'status' => \App\Enums\GateWay\TransactionStatusEnum::Success,
                'result_code' => $result_code,
                'result_message' => $result_message
            ]);
    }

    /**
     * set transaction to failed state
     *
     * @param $result_code
     * @param $result_message
     * @return bool
     */
    protected function failedTransaction($result_code, $result_message)
    {
        return Transaction::whereId($this->getTransactionId())
            ->update([
                'status' => \App\Enums\GateWay\TransactionStatusEnum::Failed,
                'result_code' => $result_code,
                'result_message' => $result_message
            ]);
    }

}