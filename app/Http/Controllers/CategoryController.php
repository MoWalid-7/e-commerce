<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Nette\Utils\Json;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('product')->get();
        return response()->json($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $category = Category::create($data);
        return response()->json([
            'success'=>true,
            'message'=>'category created successfully ',
            'data'=>$category
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = Category::find($id);
        if(!$category){
            return response()->json([
                'message'=>'the category not found'
            ],404);
        }
        return response()->json([
            'data'=>$category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
        $category = Category::find($id);
        if(!$category){
            return response()->json([
                'message'=>'category not found'
            ]);
        }
        $data = $request->validated();
        $category->update($data);
        return response()->json([
            'success'=>true,
            'message'=>'category updated successfully',
            'data'=>$category
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $category = Category::find($id);

        if(!$category){
            return response()->json([
                'message'=>'category not found'
            ], 404);
        }
        $category->delete();
        return response()->json([
            'succcess'=>true,
            'message'=>'category deleted successfully'
        ]);
    }
}
