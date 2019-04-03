<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryFieldsController extends Controller {

    /**
     * Store a newly created resource in storage.
     *
     * @param Category $category
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Category $category, Request $request)
    {
        $validated = $this->validate($request, [
            'fields' => 'required|array|exists:fields,id',
        ]);

        $category->fields()->sync($validated['fields']);

        return $this->respond('اطلاعات با موفقیت سینک شدند');
    }
}
