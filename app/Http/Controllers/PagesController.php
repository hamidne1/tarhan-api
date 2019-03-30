<?php

namespace App\Http\Controllers;

use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;

class PagesController extends Controller {

    /**
     * PagesController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth:admin')->except('index');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return PageResource::collection(
            Page::with('widgets', 'contexts')->get()
        );
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
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        $page = Page::findOrFail($id);

        if ($page->hasWidget() || $page->hasContext())
            return $this->respondWithErrors('امکان حذف وجود ندارد (به علت وجود ویدجت در این صفحه)');

        $page->delete();
        return $this->respondDeleted();
    }
}
