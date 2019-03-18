<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Models\Category;

/**
 * @group Catalogs
 *
 * Class CategoriesController
 *
 * @package App\Http\Controllers\Contents
 */
class CategoriesController extends Controller {


    /**
     * CategoriesController constructor.
     *
     */
    public function __construct()
    {
        $this->middleware('auth:admin')->except('index');
    }

    /**
     *
     * Index
     * Display a listing of the categories resources.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return CategoryResource::collection(
            Category::all()
        );
    }

    /**
     * Store
     * Store a newly created categories resource in storage.
     *
     * @bodyParam title string required The UNIQUE title of the categories.
     * @bodyParam label string required The UNIQUE label of the categories.
     * @bodyParam catalog_id string required The UNIQUE label of the categories.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $this->validate($request, [
            'title' => 'required|unique:categories,title|min:3',
            'label' => 'required|unique:categories,label|min:3',
            'catalog_id' => 'required|exists:catalogs,id'
        ]);

        $category = \App\Models\Catalog::findOrFail($validated['catalog_id'])
            ->addCategory($validated);

        return $this->respondCreated(
            'دسته بندی جدید ایجاد شد', new CategoryResource($category)
        );
    }


    /**
     * Update
     * Update a exists created category resource
     *
     * @bodyParam title string required The UNIQUE title of the category.
     * @bodyParam label string required The UNIQUE label of the category.
     *
     * @param  \Illuminate\Http\Request $request
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(\Illuminate\Http\Request $request, $id)
    {
        $validated = $this->validate($request, [
            'title' => 'required|min:3|unique:categories,title,' . $id,
            'label' => 'required|min:3|unique:categories,label,' . $id,
        ]);

        Category::findOrFail($id)->update($validated);

        return $this->respond('دسته بندی بروزرسانی شد');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {
        Category::findOrFail($id)->delete();

        return $this->respondDeleted();
    }
}
