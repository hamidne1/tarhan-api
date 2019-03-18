<?php

namespace App\Http\Controllers\Authenticate;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminResource;
use Illuminate\Http\Request;

class AdminController extends Controller {

    /**
     * UserController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     *
     * Admin
     * Get the authenticated admin
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return $this->respond(
            null, new AdminResource($request->user())
        );
    }
}
