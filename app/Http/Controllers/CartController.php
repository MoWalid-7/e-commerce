<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCartItemRequest;
use App\Http\Requests\UpdateCartItemRequest;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $cart = CartItem::with('product')
        ->where('user_id',$user->id)
        ->get();

        return response()->json([
            'success'=>true,
            'items'=>$cart
        ]);
    }
    public function store(StoreCartItemRequest $request)
    {
        $request->validated();
 
        $user = $request->user();

        $product = Product::find($request->product_id);

        $cartItem = CartItem::where('user_id',$user->id)
        ->where('product_id',$product->id)
        ->first();

        if($cartItem){
            return response()->json([
                'message'=>'This product it`s already in your cart'
            ]);
        }else{
            CartItem::create([
                'user_id'=>$user->id,
                'product_id'=>$product->id,
                'quantity'=>$request->quantity
            ]);
        }
        return response()->json([
            'success'=>true,
            'message'=>'Product added to your cart successfully'
        ]);
    }
    public function update(UpdateCartItemRequest $request, $id)
    {
        $request->validated();
        
        $user = $request->user();

        $cartItem = CartItem::where('user_id',$user->id)
        ->where('id',$id)->first();

        if(!$cartItem){
            return response()->json([
                'success'=>false,
                'message'=>'Cart Item not found'
            ],404);
        }
        
        $cartItem->update([
            'quantity'=>$request->quantity
        ]);

        return response()->json([
            'success'=>true,
            'message'=>'Cart Item Updated Successfully'
        ]);
    }
    public function destroy(Request $request,$id)
    {
        $user = $request->user();

        $cartItem = CartItem::where('user_id',$user->id)
        ->where('id',$id)->first();

        if(!$cartItem){
            return response()->json([
                'success'=>false,
                'message'=>'Cart Item not found'
            ],404);
        }
        $cartItem->delete();
        return response()->json([
            'success'=>true,
            'message'=>'Item Deleted Succesfully'
        ]);
    }
    public function clear(Request $request)
    {
        $user = $request->user();
        CartItem::where('user_id',$user->id)->delete();

        return response()->json([
            'success'=>true,
            'message'=>'Cart Cleared Successfully'
        ]);
    }
}
