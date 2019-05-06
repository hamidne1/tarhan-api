<?php

namespace App\Http\Controllers;

use App\Http\Resources\PageResource;
use App\Models\Page;
use App\Services\CM\PageService;
use Illuminate\Http\Request;

/**
 * Class PagesController
 *
 * @group Pages
 *
 * @package App\Http\Controllers\Contents
 */
class PagesController extends Controller {

    /**
     * @var PageService
     */
    protected $pageService;

    /**
     * PagesController constructor.
     * @param PageService $service
     */
    public function __construct(PageService $service)
    {
        $this->middleware('auth:admin')->except('index');
        $this->pageService = $service;
    }

    /**
     * Index
     * showing all pages has been created in
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return PageResource::collection(
            $this->pageService->get()
        );
    }

    /**
     * Store
     * create new page into application
     *
     * @bodyParam slug string(unique) required The slug of the page.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'slug' => 'required|unique:pages,slug'
        ]);

        $page = Page::create([
            'slug' => \Illuminate\Support\Str::slug($validated['slug'])
        ]);

        return $this->respondCreated(
            'یک صفحه ی جدید ایجاد شد', new PageResource($page)
        );
    }

    /**
     * Show
     * showing specific page has been created in storage
     *
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show($id)
    {
        return PageResource::collection(
            $this->pageService->show($id)
        );
    }

    /**
     * Destroy
     * delete a page from storage
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        if ($page->hasWidget() || $page->hasContext())
            return $this->respondWithErrors('امکان حذف وجود ندارد (به علت وجود ویدجت یا کانتکست در این صفحه)');

        $page->delete();
        return $this->respondDeleted();
    }
}
