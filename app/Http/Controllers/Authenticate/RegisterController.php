<?php

namespace App\Http\Controllers\Authenticate;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * @group Register Customer
 *
 * Class RegisterController
 *
 * @package App\Http\Controllers\Authenticate\Customer
 */
class RegisterController extends Controller {


    /**
     * Register
     * Register new customer into database
     *
     * @bodyParam mobile string required The mobile of the customer.
     * @bodyParam name string required The name of the customer.
     *
     * @param Request $request
     *
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $validated = $this->validate($request, [
            'mobile' => 'required|regex:/^09\d{9}$/u|unique:users,mobile',
            'name' => 'required',
        ]);

        try {
            User::create($validated);

            return $this->respondCreated('ثبت نام با موفقیت انجام گرفت');
        } catch (\Exception $exception) {
            Log::critical('Error in: customer.register: ' . $exception->getMessage());

            return $this->respondInternalError('یک خطای سرور رخ داده است.');
        }
    }


}
