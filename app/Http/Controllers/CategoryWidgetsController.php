<?php

namespace App\Http\Controllers;

use App\Enums\ContentGroupEnum;
use App\Http\Resources\WidgetResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryWidgetsController extends Controller {
    /**
     *
     * WidgetsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin')->except('index', 'show');
    }

    /**
     * Index
     * showing all widget into database
     *
     * @param Category $category
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Category $category)
    {
        return WidgetResource::collection(
            $category->widgets
        );
    }

    /**
     * Show
     * showing specific widget into database
     *
     * @param Category $category
     * @param $id
     * @return WidgetResource
     */
    public function show(Category $category, $id)
    {
        return new WidgetResource(
            $category->widgets()->findOrFail($id)
        );
    }

    /**
     * Store
     * create new widget into application
     *
     * @bodyParam col string required The col of the widget.
     * @bodyParam group string(enum) required The group of the widget.
     * @bodyParam slug string required The slug of the widget.
     * @bodyParam alt string required The alt of the widget.
     * @bodyParam href string required The href of the widget.
     * @bodyParam src string required The src of the widget.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Category $category)
    {
        $validated = $this->validate($request, [
            'col' => 'nullable|numeric',
            'slug' => 'required_without:group|unique:widgets,slug',
            'group' => [
                'required_without:slug', Rule::in(ContentGroupEnum::values())
            ],
            'alt' => 'required',
            'href' => 'required|url',
            'src' => 'required'
        ]);

        $widget = $category->widgets()->create($validated);

        return $this->respondCreated(
            'یک ویجت جدید ایجاد شد', new WidgetResource($widget)
        );
    }


    /**
     * Update
     * edit a widget from storage
     *
     * @bodyParam col string required The col of the widget.
     * @bodyParam group string(enum) required The group of the widget.
     * @bodyParam slug string required The slug of the widget.
     * @bodyParam alt string required The alt of the widget.
     * @bodyParam href string required The href of the widget.
     * @bodyParam src string required The src of the widget.
     *
     * @param Request $request
     * @param Category $category
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Category $category, $id)
    {
        $validated = $this->validate($request, [
            'col' => 'nullable|numeric',
            'group' => [
                'nullable', Rule::in(ContentGroupEnum::values())
            ],
            'alt' => 'required',
            'href' => 'required|url',
            'src' => 'required'
        ]);

        $category->widgets()->findOrFail($id)->update($validated);

        return $this->respond(
            'ویجت مورد نظر بروزرسانی شد'
        );
    }

    /**
     * Destroy
     * delete a widget from storage
     *
     * @param Category $category
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Category $category, $id)
    {
        $category->widgets()->findOrFail($id)->delete();

        return $this->respondDeleted();
    }
}
