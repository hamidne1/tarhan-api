<?php

namespace App\Http\Controllers\Authenticate\Customer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LoginController extends Controller
{


    public function login(Request $request){
    $validated = $this->validate($request,[
        'mobile' => 'required|regex:/^09\d{9}$/u|unique:users,mobile',
    ]);


    }


}
