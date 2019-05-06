<?php

namespace App\Http\Controllers;

use App\Enums\ContentGroupEnum;
use App\Http\Resources\WidgetResource;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Class PageWidgetsController
 *
 * @group Widgets
 *
 * @package App\Http\Controllers\Contents
 */
class PageWidgetsController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:admin')->except('index', 'show');
    }


    /**
     * Pages.Widgets.Index
     * showing all widgets of a specific page
     *
     * @param Page $page
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Page $page)
    {
        return WidgetResource::collection(
            $page->widgets
        );
    }

    /**
     * Pages.Widgets.Show
     * showing specific widget of specific page
     *
     * @param Page $page
     * @param $id
     * @return WidgetResource
     */
    public function show(Page $page, $id)
    {
        return new WidgetResource(
            $page->widgets()->findOrFail($id)
        );
    }

    /**
     * Pages.Widgets.Store
     * create new widget and attach to given page
     *
     * @bodyParam col numeric nullable The col of the widgets.
     * @bodyParam slug string required The slug of the widgets.
     * @bodyParam group enum required The group of the widgets.
     * @bodyParam alt string required The alt of the widgets.
     * @bodyParam href url required The href of the widgets.
     * @bodyParam src url required The src of the widgets.
     *
     * @param Request $request
     * @param Page $page
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Page $page)
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
        $widget = $page->widgets()->create($validated);

        return $this->respondCreated(
            'یک محتوای تصویری جدید ایجاد شد', new WidgetResource($widget)
        );
    }

    /**
     * Pages.Widgets.Update
     * edit a specific widget of a page
     *
     * @bodyParam col numeric nullable The col of the widgets.
     * @bodyParam group enum required The group of the widgets.
     * @bodyParam alt string required The alt of the widgets.
     * @bodyParam href url required The href of the widgets.
     * @bodyParam src url required The src of the widgets.
     *
     *
     * @param Request $request
     * @param Page $page
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, Page $page, $id)
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

        $page->widgets()->findOrFail($id)->update($validated);

        return $this->respond(
            'به روزرسانی با موفقیت انجام شد'
        );
    }

    /**
     * Pages.Widgets.Destroy
     * delete a widget into database
     *
     * @param Page $page
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Page $page, $id)
    {
        $page->widgets()->findOrFail($id)->delete();

        return $this->respondDeleted();
    }
}
