<?php

namespace App\Http\Controllers;

use App\Http\Resources\PortfolioResource;
use App\Models\Category;
use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfoliosController extends Controller {

    /**
     * PortfolioController constructor.
     *
     */
    public function __construct()
    {
        $this->middleware('auth:admin')->except('show', 'index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return PortfolioResource::collection(
            Portfolio::all()
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'title' => 'required|string',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
            'category_id' => 'required|exists:categories,id'
        ]);

        $portfolio = Category::findOrFail($validated['category_id'])
            ->portfolios()->create($validated);

        return $this->respondCreated(
            'یک نمونه کار جدید ایجاد شد', new PortfolioResource($portfolio)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param Portfolio $portfolio
     * @return PortfolioResource
     */
    public function show(Portfolio $portfolio)
    {
        return new PortfolioResource(
            $portfolio
        );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Portfolio $portfolio)
    {
        $validated = $this->validate($request, [
            'title' => 'required|string',
            'description' => 'nullable|string',
            'link' => 'nullable|url'
        ]);

        $portfolio->update($validated);

        return $this->respond(
            'بروز رسانی با موفقیت انجام شد'
        );
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Portfolio $portfolio
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Portfolio $portfolio)
    {
        $portfolio->delete();

        return $this->respondDeleted();
    }
}
