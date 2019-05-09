<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContextResource;
use App\Http\Resources\TariffResource;
use App\Models\Category;
use App\Models\Context;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;

class CategoryTariffsController extends Controller
{
    /**
     *
     * ContextsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin')->except('index','show');
    }


    /**
     * Index
     * showing all context of database
     *
     * @param Category $category
     * @return AnonymousResourceCollection
     */
    public function index(Category $category)
    {
        return TariffResource::collection(
            $category->tariffs
        );
    }

    /**
     * Show
     * showing specific context into database
     *
     * @param Category $category
     * @param $id
     * @return TariffResource
     */
    public function show(Category $category, $id)
    {
        return new TariffResource(
            $category->tariffs()->findOrFail($id)
        );
    }

    /**
     * Store
     * create new widget into application
     *
     * @bodyParam parent_id integer(exists) optional The parent_id of the context.
     * @bodyParam slug string required The slug of the context.
     * @bodyParam icon string optional The icon of the context.
     * @bodyParam href string optional The href of the context.
     * @bodyParam value string required The value of the context.
     *
     * @param Request $request
     * @param Category $category
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request, Category $category)
    {
        $validated = $this->validate($request, [
            'parent_id' => 'nullable|exists:contexts,id',
            'slug' => 'required|unique:contexts,slug',
            'icon' => 'nullable',
            'href' => 'nullable|url',
            'value' => 'required',
        ]);

        $context = $category->contexts()->create($validated);

        return $this->respondCreated(
            'یک محتوای متنی جدید ایجاد شد', new ContextResource($context)
        );
    }


    /**
     * Update
     * edit a widget from storage
     *
     * @bodyParam parent_id integer(exists) optional The parent_id of the context.
     * @bodyParam slug string required The slug of the context.
     * @bodyParam icon string optional The icon of the context.
     * @bodyParam href string optional The href of the context.
     * @bodyParam value string required The value of the context.
     *
     * @param Request $request
     * @param Category $category
     * @param int $id
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(Request $request, Category $category, $id)
    {
        $validated = $this->validate($request, [
            'parent_id' => 'nullable|exists:contexts,id',
            'icon' => 'nullable',
            'href' => 'nullable|url',
            'value' => 'required',
        ]);

        Context::findOrFail($id)->update($validated);

        return $this->respond(
            'کانتکست مورد نظر بروزرسانی شد'
        );
    }

    /**
     * Destroy
     * delete a specific context from storage
     *
     * @param Category $category
     * @param int $id
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Category $category, $id)
    {
        $category->contexts()->findOrFail($id)->delete();

        return $this->respondDeleted();
    }
}
