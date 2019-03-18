<?php

namespace App\Http\Controllers\Authenticate;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller {

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
    protected $decayMinutes = 5;

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * provider guard for this controller
     *
     * @return \Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    public function guard()
    {
        return Auth::guard('admin');
    }

    /**
     * the register user construct
     *
     */
    public function __construct()
    {
        $this->middleware('guest:admin')->except('logout');
        $this->middleware('auth:admin')->only('logout');
    }

    /**
     * Login
     * Login the admin in to the application
     *
     * @bodyParam username string required The username of the admin.
     * @bodyParam password string required The password of the admin.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        $validated = $this->validate($request, [
            $this->username() => 'required',
            'password' => 'required',
        ]);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            $this->sendLockoutResponse($request);
        }

        if ($token = $this->guard()->attempt($validated)) {
            $this->clearLoginAttempts($request);

            return $this->respond('احراز هویت شما موفق بود.', $token);
        }

        $this->incrementLoginAttempts($request);

        throw ValidationException::withMessages([
            $this->username() => [
                Lang::get('auth.failed')
            ],
        ]);

    }

    /**
     * Logout
     * Logout the admin from home
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        $this->guard()->logout();

        return $this->respond('از صفحه کاربری خود خارج شدید');

    }
}
