<?php

namespace App\Http\Controllers;

use App\Http\Resources\WidgetResource;
use App\Models\Page;
use App\Models\Widget;
use Illuminate\Http\Request;

class WidgetsController extends Controller {

    /**
     *
     * WidgetsController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'page_id' => 'required|exists:pages,id',
            'category_id' => 'nullable|exists:categories,id',
            'col' => 'required',
            'group' => [
                'required', \Illuminate\Validation\Rule::in(\App\Enums\ContentGroupEnum::values())
            ],
            'title' => 'required',
            'alt' => 'required',
            'href' => 'required|url',
            'src' => 'required'
        ]);

        $widget = Page::findOrFail($validated['page_id'])->addWidget($validated);

        return $this->respondCreated(
            'یک ویجت جدید ایجاد شد', new WidgetResource($widget)
        );
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request, $id)
    {
        $validated = $this->validate($request, [
            'page_id' => 'required|exists:pages,id',
            'category_id' => 'nullable|exists:categories,id',
            'col' => 'required',
            'group' => [
                'required', \Illuminate\Validation\Rule::in(\App\Enums\ContentGroupEnum::values())
            ],
            'title' => 'required',
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
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        Widget::findOrFail($id)->delete();

        return $this->respondDeleted();
    }
}
