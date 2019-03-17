<?php

namespace App\Http\Controllers\Authenticate\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller {

    /**
     * login the customer
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $this->validate($request, [
            'mobile' => 'required|regex:/^09\d{9}$/u|exists:users,mobile',
        ]);

        // TODO: send 'verify_code' by sms to this mobile
        return $this->respond('کد تایید به شماره موبایل وارد شده ارسال شد');
    }


}
