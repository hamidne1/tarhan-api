<?php

namespace App\Http\Controllers\Authenticate;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;

class CustomerController extends Controller {

    /**
     * CustomerController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    /**
     * return user if has logged in
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return $this->respond(
            null, new CustomerResource($request->user())
        );
    }
}
