<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContextResource;
use App\Models\Context;
use Illuminate\Http\Request;

/**
 * Class ContextsController
 *
 * @group Contexts
 *
 * @package App\Http\Controllers\Contents
 */
class ContextsController extends Controller {
    /**
     *
     * ContextsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin')->except('index');
    }


    /**
     * Index
     * showing all context of database
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return ContextResource::collection(
            Context::all()
        );
    }

    /**
     * Show
     * showing specific context into database
     *
     * @param Context $context
     * @return ContextResource
     */
    public function show(Context $context)
    {
        return new ContextResource($context);
    }

    /**
     * Store
     * create new widget into application
     *
     * @bodyParam page_id integer(exists) optional The page_id of the context.
     * @bodyParam category_id integer(exists) optional The category_id of the context.
     * @bodyParam parent_id integer(exists) optional The parent_id of the context.
     * @bodyParam slug string required The slug of the context.
     * @bodyParam icon string optional The icon of the context.
     * @bodyParam href string optional The href of the context.
     * @bodyParam value string required The value of the context.
     *
     * @param ContextRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {

        $validated = $this->validate($request, [
            'page_id' => 'nullable|exists:pages,id',
            'category_id' => 'nullable|exists:categories,id',
            'parent_id' => 'nullable|exists:contexts,id',
            'slug' => 'required|unique:contexts,slug',
            'icon' => 'nullable',
            'href' => 'nullable|url',
            'value' => 'required',
        ]);

        $context = Context::create($validated);

        return $this->respondCreated(
            'یک محتوای متنی جدید ایجاد شد', new ContextResource($context)
        );
    }


    /**
     * Update
     * edit a widget from storage
     *
     * @bodyParam page_id integer(exists) optional The page_id of the context.
     * @bodyParam category_id integer(exists) optional The category_id of the context.
     * @bodyParam parent_id integer(exists) optional The parent_id of the context.
     * @bodyParam slug string required The slug of the context.
     * @bodyParam icon string optional The icon of the context.
     * @bodyParam href string optional The href of the context.
     * @bodyParam value string required The value of the context.
     *
     * @param ContextRequest $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $validated = $this->validate($request, [
            'page_id' => 'nullable|exists:pages,id',
            'category_id' => 'nullable|exists:categories,id',
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
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        Context::findOrFail($id)->delete();

        return $this->respondDeleted();
    }
}
