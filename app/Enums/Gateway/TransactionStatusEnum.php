<?php

namespace App\Enums\Gateway;

use App\Enums\Enum;

class TransactionStatusEnum extends Enum {

    const Init = 'Init';
    const Success = 'Success';
    const Failed = 'Failed';
}