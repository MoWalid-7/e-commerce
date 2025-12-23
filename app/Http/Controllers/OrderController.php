<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB ;

class OrderController extends Controller
{
    public function checkOut(CheckoutRequest $request)
    {
        $request->validated();

        $user = $request->user();
        $cartItem = CartItem::with('product')->where('user_id',$user->id)->get();
        if($cartItem->isEmpty()){
            return response()->json([
                'message'=>'Please added products to your cart first'
            ]);
        }
        // dd($cartItem);
        try {
            return DB::transaction(function () use ($user, $cartItem, $request) {  
                $total = $cartItem->sum(function ($item) {
                    return $item->product->price * $item->quantity;
                });
                $order = Order::create([
                    'user_id' => $user->id,
                    'subtotal' => $total,
                    'total' => $total, 
                    'address' => $request->address,
                    'payment_method' => $request->payment_method,
                    'status' => 'pending',
                ]);

                foreach ($cartItem as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'price' => $item->product->price,
                        'quantity' => $item->quantity,
                    ]);

                }
                $user->cartItems()->delete(); 
                return response()->json([
                    'message' => 'Order created successfully',
                    'order_id' => $order->id
                ], 201);
            });

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
        }

    }
    public function index(Request $request)
    {
        $user = $request->user();
        $order = Order::with('user','orderItem.product')
        ->where('user_id',$user->id)->get();
        if($order->isEmpty()){
            return response()->json([
                'message'=>'Your order not found'
            ]);
        }
        return response()->json([
            'order'=>$order
        ]);
    }
    public function show(Request $request,$id)
    {
        $user  = $request->user();
        $order = Order::with('user','orderItem.product')
        ->where('user_id',$user->id)
        ->where('id',$id)->first();  
        if(!$order){
            return response()->json([
                'message'=>'Your order not found'
            ]);
        } 
        return response()->json([
            'order'=>$order
        ]);
    }
    public function cancel(Request $request,$id)
    {
        // cancel this order
        $user = $request->user();
        DB::transaction(function ()use($user,$id){
            $order = Order::where('user_id',$user->id)
            ->where('id',$id)->first();
            if(!$order){
                return response()->json([
                    'message'=>'Your order not found'
                ]);
            }
            
            if($order->status !== 'pending'){
                return response()->json([
                    'message'=>'Your Order cannot cancelled'
                ]);
            }
            foreach($order->orderItem as $item){
                CartItem::updateOrCreate(  [
                    'user_id' => $user->id,
                    'product_id' => $item->product_id
                    ],[
                    'quantity' => DB::raw('quantity + '.$item->quantity)
                ]);
            }

            $order->update([
                'status'=>'cancelled'
            ]);

        });
        return response()->json([
            'message'=>'Your Order cancelled successfully'
        ]);

    }
}
