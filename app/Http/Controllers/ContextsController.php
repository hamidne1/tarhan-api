<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContextResource;
use App\Models\Context;
use Illuminate\Http\Request;

class ContextsController extends Controller {
    /**
     *
     * ContextsController constructor.
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
            'page_id' => 'nullable|exists:pages,id',
            'category_id' => 'nullable|exists:categories,id',
            'parent_id' => 'nullable|exists:contexts,id',
            'slug' => 'required|unique:contexts,slug',
            'icon' => 'nullable',
            'href' => 'nullable|url',
            'value' => 'required',
        ]);

        $context = \App\Models\Page::findOrFail($validated['page_id'])->addContext($validated);

        return $this->respondCreated(
            'یک محتوای متنی جدید ایجاد شد', new ContextResource($context)
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
            'category_id' => 'nullable|exists:categories,id',
            'page_id' => 'required|exists:pages,id',
            'parent_id' => 'nullable|exists:contexts,id',
            'slug' => 'required|unique:contexts,slug',
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
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        Context::findOrFail($id)->delete();

        return $this->respondDeleted();
    }
}
