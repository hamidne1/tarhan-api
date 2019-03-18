<?php

namespace App\Http\Controllers\Authenticate;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;


/**
 * @group Login Customer
 *
 * Class LoginController
 *
 * @package App\Http\Controllers\Authenticate\Customer
 */

class LoginController extends Controller {

    use ThrottlesLogins;

    /**
     * max try to attempt number
     *
     * @var int
     */
    protected $maxAttempts = 5;

    /**
     * time by minute username has been locked
     *
     * @var int
     */
    protected $decayMinutes = 2;

    /**
     * the register user construct
     *
     */
    public function __construct()
    {
        $this->middleware('guest:customer')->except('logout');
        $this->middleware('auth:customer')->only('logout');
    }

    /**
     * provider the guard
     *
     * @return mixed
     */
    protected function guard()
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
     * Login
     * Login the customer in to thw application
     *
     *  @bodyParam mobile string required The mobile of the customer.
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
     * Verify
     * Verify the customer verify_code for login
     *
     * @bodyParam mobile string required The mobile of the customer.
     * @bodyParam verify_code integer required the verify code that sended to mobile
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

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            $this->sendLockoutResponse($request);
        }

        if ($token = $this->guard()->attempt($validated)) {
            return $this->sendLoginResponse($request, $token);
        }

        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages([
            'mobile' => [
                Lang::get('auth.failed')
            ],
        ]);
    }


    /**
     * Logout
     * Logout the customer from home
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return $this->respond('از صفحه کاربری خود خارج شدید');

    }


    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $token
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendLoginResponse(Request $request, $token)
    {
        $this->clearLoginAttempts($request);

        return $this->respond('احراز هویت شما موفق بود.', $token);
    }

}
