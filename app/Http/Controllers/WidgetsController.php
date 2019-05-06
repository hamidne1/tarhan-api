<?php

namespace App\Http\Controllers;

use App\Enums\ContentGroupEnum;
use App\Http\Resources\WidgetResource;
use App\Models\Widget;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class WidgetsController
 *
 * @group Widgets
 *
 * @package App\Http\Controllers\Contents
 */
class WidgetsController extends Controller {

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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return WidgetResource::collection(
            Widget::all()
        );
    }

    /**
     * Show
     * showing specific widget into database
     *
     * @param Widget $widget
     * @return WidgetResource
     */
    public function show(Widget $widget)
    {
        return new WidgetResource($widget);
    }

    /**
     * Store
     * create new widget into application
     *
     * @bodyParam page_id integer(exists) optional The page_id of the widget.
     * @bodyParam category_id integer(exists) optional The category_id of the widget.
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
    public function store(Request $request)
    {

        $validated = $this->validate($request, [
            'page_id' => 'nullable|exists:pages,id',
            'category_id' => 'nullable|exists:categories,id',
            'col' => 'nullable|numeric',
            'slug' => 'required_without:group|unique:widgets,slug',
            'group' => [
                'required_without:slug', Rule::in(ContentGroupEnum::values())
            ],
            'alt' => 'required',
            'href' => 'required|url',
            'src' => 'required'
        ]);

        $widget = Widget::create($validated);

        return $this->respondCreated(
            'یک ویجت جدید ایجاد شد', new WidgetResource($widget)
        );
    }


    /**
     * Update
     * edit a widget from storage
     *
     * @bodyParam page_id integer(exists) optional The page_id of the widget.
     * @bodyParam category_id integer(exists) optional The category_id of the widget.
     * @bodyParam col string required The col of the widget.
     * @bodyParam group string(enum) required The group of the widget.
     * @bodyParam slug string required The slug of the widget.
     * @bodyParam alt string required The alt of the widget.
     * @bodyParam href string required The href of the widget.
     * @bodyParam src string required The src of the widget.
     *
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $validated = $this->validate($request, [
            'page_id' => 'nullable|exists:pages,id',
            'category_id' => 'nullable|exists:categories,id',
            'col' => 'nullable|numeric',
            'group' => [
                'nullable', Rule::in(ContentGroupEnum::values())
            ],
            'alt' => 'required',
            'href' => 'required|url',
            'src' => 'required'
        ]);

        Widget::findOrFail($id)->update($validated);

        return $this->respond(
            'ویجت مورد نظر بروزرسانی شد'
        );
    }

    /**
     * Destroy
     * delete a widget from storage
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        Widget::findOrFail($id)->delete();

        return $this->respondDeleted();
    }
}
