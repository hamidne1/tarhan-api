<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Portfolio;
use Illuminate\Http\Request;

class PortfolioController extends Controller
{


    /**
     * PortfolioController constructor.
     *
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return array
     */
    public function index()
    {
        $portfolios = Portfolio::all();
        $all_portfolios = [];
        $i = 0;
        foreach ($portfolios as $portfolio) {
            array_merge($all_portfolios
                , array($i => array_merge($portfolio->toArray()
                    , array('multimedia' => $portfolio->multimedia->toArray()
                    ))));
            $i++;
        }
        return $all_portfolios;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {


        $validated = $this->validate($request, [
            'title' => 'required',
            'description' => 'required',
            'path' => 'nullable',
            'category_id' => 'required|numeric|exists:categories,id'
        ]);

        $fields = $this->getFields($request->category_id);

        $for_field_validation = [
            $fields[0]['title'] => 'required|exists:fields,title',
            $fields[0]['icon'] => 'required|exists:fields,icon',
        ];
        $validated_fields = $this->validate($request, $for_field_validation);



    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param $categoryId
     * @return array
     */
    public function getFields($categoryId)
    {

        return Category::findOrFail($categoryId)->fields()->get()->toArray();

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
