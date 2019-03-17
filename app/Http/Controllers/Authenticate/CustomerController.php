<?php

namespace App\Http\Controllers\Authenticate;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;

/**
 * @group Customer
 *
 * Class CustomerController
 *
 * @package App\Http\Controllers\Authenticate
 */
class CustomerController extends Controller {

    /**
     * CustomerController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    /**
     * Customer
     * Get the authenticated customer
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
