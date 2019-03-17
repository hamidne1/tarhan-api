<?php

namespace App\Http\Controllers\Authenticate\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller {

    /**
     * provider the guard
     *
     * @return mixed
     */
    public function guard()
    {
        return Auth::guard('customer');
    }

    /**
     * provider the username key
     *
     * @return string
     */
    public function username()
    {
        return 'mobile';
    }

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


    /**
     * verify the customer verify code for login
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function verify(Request $request)
    {
        $validated = $this->validate($request, [
            'mobile' => 'required|regex:/^09\d{9}$/u|exists:users,mobile',
            'verify_code' => 'required'
        ]);

        if ($token = $this->guard()->attempt($validated)) {
            return $this->respond('با موفقیت لاگین شدید', $token);
        }

        throw ValidationException::withMessages([
            'verify_code' => [
                Lang::get('auth.failed')
            ],
        ]);
    }


    /**
     * logout customer
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return $this->respond('از صفحه کاربری خود خارج شدید');

    }


}
