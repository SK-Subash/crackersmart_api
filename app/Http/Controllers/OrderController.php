<?php

namespace App\Http\Controllers;


use App\Models\cart;
use App\Models\users;
use App\Models\brand;
use App\Models\products;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Str;

use App\Models\order;

class OrderController extends Controller
{
    function order_details(Request $request){
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|string|min:1|max:5',
            'order_payment_method' => 'required|string|min:5|max:20',
        ]);
        try{
            if($validate->fails()){
                return response()->json($validate->errors()->toArray());
            }
            else{
                $bill = [];
                $product_bill_final= [];
                $users = users::where(['id'=>$request->user_id])->exists();
                if($users){
                    $user_details = users::where(['id'=>$request->user_id])->get();                
                    $product_details = DB::table('cart')->join('products','products.id','=','product_id')->join('brands','brands.id','=','products.brand_id')->join('categories','categories.id','=','products.category_id')->where(['cart.user_id'=>$request->user_id])->get(['brands.brand_name','categories.category_name','products.product_name','products.product_image','products.product_link','products.product_mrp','products.product_discount','products.stock_quantity','cart.quantity']);
                    if($product_details){
                        $payment_method['payment_method'] = $request->order_payment_method;
                        $order_id['order_id'] =  Str::random(3).date('YmdHis').Str::random(4).mt_rand(100000,999999);
                        foreach ($user_details as $user) {
                            $bill['user_details']['name'] = $user->name;
                            $bill['user_details']['email'] = $user->email;
                            $bill['user_details']['phone_number'] = $user->phone_number;
                            $bill['user_details']['address'] = json_decode($user->address);
                        }
                        foreach ( $product_details as $product){
                            $discount = $product->product_mrp * ( $product->product_discount / 100 );
                            $product_amount =  $product->product_mrp * $product->quantity;
                            $product_bill = $product_amount - $discount;
                            $bill['bill_details']['brand_name'] =  $product->brand_name;
                            $bill['bill_details']['category_name'] =  $product->category_name;
                            $bill['bill_details']['product_name'] =  $product->product_name;
                            $bill['bill_details']['product_image'] =  $product->product_image;
                            $bill['bill_details']['product_link'] =  $product->product_link;
                            $bill['bill_details']['product_mrp'] =  $product->product_mrp;
                            $bill['bill_details']['product_discount'] =  $product->product_discount;
                            $bill['bill_details']['stock_quantity'] =  $product->stock_quantity;
                            $bill['bill_details']['quantity'] =  $product->quantity;

                            $bill['bill_details']['product_amount'] =  json_encode($product_amount);
                            $bill['bill_details']['discount'] = json_encode($discount);
                            $bill['bill_details']['product_bill'] = json_encode($product_bill);
                    
                            $product_bill_final[] = $bill['bill_details'];
                        }                       
                        $order = new order;
                        $order->order_id = $order_id['order_id'];
                        $order->user_details = json_encode($bill['user_details']);
                        $order->order_details = json_encode($product_bill_final);
                        $order->order_status = 'preparing for packing';
                        $order->order_payment_method =  $payment_method['payment_method'];
                        $order->save();
                        
                        $orders['order_id'] = $order->order_id;
                        $orders['order_status'] = $order->order_status;
                        $orders['payment_method'] = $order->order_payment_method;
                        
                        return response()->json(array_merge($orders,$bill['user_details'],$product_bill_final));   
                    }
                    else{
                        return response()->json('product not Found');
                    }
                }
                else {
                    return response()->json('user not Found');
                }
            }
        }
        catch(\Exception $exception){
            return response()->json($exception->getMessage());
        } 
        catch (\Illuminate\Database\QueryException $exception ){
            return response()->json($exception->getMessage());
        }
    }
    function orders (Request $request) {
        return 'working';
    }
}