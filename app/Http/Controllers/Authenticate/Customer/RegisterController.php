<?php

namespace App\Http\Controllers\Authenticate\Customer;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller {


    /**
     * register new user into database
     *
     * @param Request $request
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function register(Request $request)
    {
        $validated = $this->validate($request, [
            'mobile' => 'required|regex:/^09\d{9}$/u|unique:users,mobile',
            'name' => 'required',
        ]);

        DB::beginTransaction();
        try {
            User::create($validated);

            DB::commit();
            return $this->respondCreated('ثبت نام با موفقیت انجام گرفت');
        } catch (\Exception $e) {
            \Log::critical('Error in: user.store: ' . $e->getMessage());
            \DB::rollBack();

            return $this->respondInternalError('یک خطای سرور رخ داده است.');
        }
    }


}
