<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContextResource;
use App\Models\Page;
use Illuminate\Http\Request;

/**
 * Class PageContextsController
 *
 * @group Contexts
 *
 * @package App\Http\Controllers\Contents
 */
class PageContextsController extends Controller {

    public function __construct()
    {
        $this->middleware('auth:admin')->except('index');
    }

    /**
     * Pages.Contexts.Index
     * showing all context for specific page resource
     *
     * @param Page $page
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(Page $page)
    {
        return ContextResource::collection(
            $page->contexts
        );
    }

    /**
     * Pages.Contexts.Show
     * show specific resource of specific page resource
     *
     * @param Page $page
     * @param $id
     * @return ContextResource
     */
    public function show(Page $page, $id)
    {
        return new ContextResource(
            $page->contexts()->findOrFail($id)
        );
    }

    /**
     * Pages.Contexts.Store
     * create a context and attach a given page
     *
     * @bodyParam parent_id integer(exists) required The parent_id of the contexts.
     * @bodyParam slug string(unique) required The slug of the contexts.
     * @bodyParam icon string nullable The icon of the contexts.
     * @bodyParam href string nullable The href of the contexts.
     * @bodyParam value string require The value of the contexts.
     *
     * @param Request $request
     * @param Page $page
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request, Page $page)
    {
        $validated = $this->validate($request, [
            'parent_id' => 'nullable|exists:contexts,id',
            'slug' => 'required|unique:contexts,slug',
            'icon' => 'nullable',
            'href' => 'nullable|url',
            'value' => 'required',
        ]);

        $context = $page->contexts()->create($validated);

        return $this->respondCreated(
            'یک محتوای متنی جدید ایجاد شد و به صفحه ی مورد نظر اتچ شد', new ContextResource($context)
        );
    }

    /**
     * Pages.Contexts.Update
     * edit a specif context of page
     *
     * @bodyParam parent_id integer(exists) required The parent_id of the contexts.
     * @bodyParam icon string nullable The icon of the contexts.
     * @bodyParam href string nullable The href of the contexts.
     * @bodyParam value string require The value of the contexts.
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
            'parent_id' => 'nullable|exists:contexts,id',
            'icon' => 'nullable',
            'href' => 'nullable|url',
            'value' => 'required',
        ]);
        $page->contexts()->findOrFail($id)->update($validated);

        return $this->respond(
            'به روزرسانی با موفقیت انجام شد'
        );
    }

    /**
     * Pages.Contexts.Destroy
     * destroy a specific resource from database
     *
     * @param Page $page
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Page $page, $id)
    {
        $page->contexts()->findOrFail($id)->delete();

        return $this->respondDeleted();
    }
}
