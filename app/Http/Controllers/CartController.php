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

class CartController extends Controller
{
   function add_cart(Request $request) {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|string|min:1|max:5',
            'product_id' => 'required|string|min:1|max:5',
            'quantity' => 'required|string|min:1|max:5',
        ]);
        try{
            if($validate->fails()){
                return response()->json($validate->errors()->toArray());
            }
            else{
                if(!empty(users::where(['id'=>$request->user_id])->exists())){
                    if(!empty(products::where(['id'=>$request->product_id])->exists())){
                        $product = products::where(['id'=>$request->product_id])->first();
                        if($product->stock_quantity > $request->quantity){
                            if(empty(cart::where(['user_id'=>$request->user_id,'product_id'=> $request->product_id])->exists())){
                                $cart = new cart;
                                $cart->user_id = $request->user_id;
                                $cart->product_id = $request->product_id;
                                $cart->quantity = $request->quantity;
                                $cart->save();
                                return response()->json(['success'=> 'Product successfully added in cart']);
                            }
                            else{
                                return response()->json(['error'=> 'Product already added in cart']);
                            }
                        }
                        else{
                            return response()->json(['error'=> 'available quantity is '.$product->stock_quantity. ' only']);
                        }
                    }
                    else{
                        return response()->json(['error'=> 'Product not Found']);
                    }
                }
                else{
                    return response()->json(['error'=> 'User not Found']);
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

   function remove_cart(Request $request) {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|string|min:1|max:5',
            'cart_id' => 'required|string|min:1|max:6',
        ]);
        try{
            if($validate->fails()){
                return response()->json($validate->errors()->toArray());
            }
            else{
                if(!empty(users::where(['id'=>$request->user_id])->exists())){
                   if(!empty(cart::where(['user_id'=>$request->user_id,'id'=> $request->cart_id])->exists())){
                        cart::where(['user_id'=> $request->user_id,'id' => $request->cart_id])->delete();
                        return response()->json(['success'=> 'Product successfully removed from cart']);
                    }
                    else{
                        return response()->json(['error'=> 'Product not found in cart']);
                    }
                }     
                else{
                    return response()->json(['error'=> 'User not Found']);
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

    function add_quantity(Request $request) {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|string|min:1|max:5',
            'cart_id' => 'required|string|min:1|max:6',
            'quantity' => 'required|string|min:1|max:5',
        ]);
        try{
            if($validate->fails()){
                return response()->json($validate->errors()->toArray());
            }
            else{
                if(!empty(users::where(['id'=>$request->user_id])->exists())){                        
                    if(!empty(cart::where(['user_id'=>$request->user_id,'id'=> $request->cart_id])->exists())){
                        $product_detail = cart::where(['user_id'=>$request->user_id,'id'=> $request->cart_id])->first();
                        $product = products::where(['id'=>$product_detail->product_id])->first();
                        if($product->stock_quantity > $request->quantity){
                            $cart = cart::where(['user_id'=> $request->user_id,'id' => $request->cart_id])->first();
                            $cart->quantity = $request->quantity;
                            $cart->save();
                            return response()->json(['success'=> 'Product quantity updated successfully']);
                        }
                        else{
                            return response()->json(['error'=> 'available quantity is '.$product->stock_quantity. ' only']);
                        }
                    }
                    else{
                        return response()->json(['error'=> 'Product not found in cart']);
                    }
                }
                else{
                    return response()->json(['error'=> 'User not Found']);
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

    function view_cart(Request $request){
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|string|min:1|max:5',
        ]);
           
        try{
            if($validate->fails()){
                return response()->json($validate->errors()->toArray());
            }
            // $data = DB::table('cart')->join('products','products.id','=','product_id')->join('brands','brands.id','=','products.brand_id')->where(['cart.user_id'=>$request->user_id])->get(['brands.brand_name','products.product_name','products.product_image','products.product_mrp','products.product_discount','products.stock_quantity','cart.quantity']);
            // if($data){
            //     return response()->json($data);
            // }
            // else {
            //     return response()->json('Products not Found');
            // }
            $bill = [];
            $product_bill_final= [];
            $product_details = DB::table('cart')->join('products','products.id','=','product_id')->join('brands','brands.id','=','products.brand_id')->join('categories','categories.id','=','products.category_id')->where(['cart.user_id'=>$request->user_id])->get(['brands.brand_name','categories.category_name','products.product_name','products.product_image','products.product_link','products.product_mrp','products.product_discount','products.stock_quantity','cart.quantity','cart.id']);
            if($product_details){
                foreach ( $product_details as $product){
                    $discount = $product->product_mrp * ( $product->product_discount / 100 );
                    $product_amount =  $product->product_mrp * $product->quantity;
                    $product_bill = $product_amount - $discount;
                    $bill['bill_details']['id'] =  $product->id;
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
                return response()->json($product_bill_final);   
            }
            else{
                return response()->json('product not Found in cart');
            }
        }
        catch(\Exception $exception){
            return response()->json($exception->getMessage());
        } 
        catch (\Illuminate\Database\QueryException $exception ){
            return response()->json($exception->getMessage());
        } 
    }

}
?>