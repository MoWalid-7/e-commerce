<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\json;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category')->get();
       // Show All Products 
       return response()->json($products);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        // upload photos
        if($request->hasFile('main_image')){
            $path = $request->file('main_image')->store('images','public');
            $data['main_image']=$path;  
        }
        // Create & Validate 
        $product = Product::create($data);
        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product
        ]) ;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Show one product
        $product = Product::find($id); 
        if (!$product){
            return response()->json([
                'success'=>false,
                'message'=>'product not found'
            ],404);
        }
        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, string $id)
    {
        $product = Product::find($id);

        if(!$product){
            return response()->json([
                'success'=>false,
                'message'=>'product not found'
            ],404);
        }

        $data = $request->validated();
        
        if($request->hasFile('main_image')){

            if ($product->main_image && Storage::disk('public')->exists($product->main_image)) {
                Storage::disk('public')->delete($product->main_image);
            } 

            $path = $request->file('main_image')->store('images','public');
            $data['main_image']=$path;  
        }
        
        $product->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        if(!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        if($product->main_image && Storage::disk('public')->exists($product->main_image)) {
            Storage::disk('public')->delete($product->main_image);
        }
        $product->delete();

        return response()->json([
            'sucsses'=>true,
            'message'=>'product deleted successfully '
        ]);
    }
}
