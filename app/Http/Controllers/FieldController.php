<?php

namespace App\Http\Controllers;

use App\Http\Resources\FieldResource;
use App\Models\Category;
use App\Models\Field;
use Illuminate\Http\Request;

class FieldController extends Controller
{


    /**
     * FieldController constructor.
     *
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }


    /**
     * Store
     * Store a newly created field resource in storage.
     *
     * @bodyParam title string required The  title of the field.
     * @bodyParam label string required The  icon of the field.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'title' => 'required',
            'icon' => 'required'
        ]);

        $validated_id = $this->validate($request, [
            'category_id' => 'required|exists:categories,id'
        ]);

        $category = Category::find((int)$validated_id);

        $field = $category->addFields($validated);

        return $this->respondCreated(
            'یک فیلد جدید ایجاد شد', new FieldResource($field)
        );
    }

    /**
     * show
     * Display a listing of the fields resources.
     *
     * @param $id
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return FieldResource::collection($category->fields);

    }


    /**
     * Update
     * Update a exists created field resource
     *
     * @bodyParam title string required The  title of the field.
     * @bodyParam icon string required The  icon of the field.
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
            'title' => 'required',
            'icon' => 'required',
        ]);

        Field::findOrFail($id)->update($validated);

        return $this->respond('فیلد بروزرسانی شد');
    }

    /**
     * Remove the specified field from storage.
     *
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy($id)
    {

        Field::findOrFail($id)->delete();

        return $this->respondDeleted();
    }
}
