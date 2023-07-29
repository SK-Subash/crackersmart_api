<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\Category;
use App\Models\brand;
use App\Models\users;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use DB;


class ProductController extends Controller
{
    function add_product(Request $request){
        $validate = Validator::make($request->all(), [
            'brand_name' => 'required|string|min:3|max:30',
            'category_name' => 'required|string|min:3|max:30',
            'product_name' => 'required|string|min:3|max:30',
            'image' => 'mimes:jpg,jpeg,bmp,png,svg|image|required|max:2100',
            'product_mrp' => 'required|string|max:10',
            'product_discount' => 'required|string|max:10',
            'stock_quantity' => 'required|string|max:10',
        ]);
        try{
            $brands = brand::where(['brand_name'=>$request->brand_name])->exists();
            if($validate->fails()){
                return response()->json($validate->errors()->toArray());
            }
            else if(empty($brands)) {
                
                $filename = date('YmdHis') .'.' . $request->image->extension();
                $request->image->move(public_path('product/icons/'), $filename);
               
                $brand = new brand;
                $brand->brand_name = $request->brand_name;
                $brand->save();
                $category = new Category;
                $category->brand_id = $brand->id;
                $category->category_name = $request->category_name;
                $category->save();
                $imagepath = $filename;
                $product = new products;
                $product->brand_id = $brand->id;
                $product->category_id = $category->id;
                $product->product_name = $request->product_name;
                $product->product_image = $imagepath;
                $product->product_link = $request->product_link != "" ? $request->product_link : null;
                $product->product_mrp = $request->product_mrp;
                $product->product_discount = $request->product_discount;
                $product->stock_quantity = $request->stock_quantity;
                $product->save();
                
                return response()->json(['success'=> $request->brand_name.' Brand Created, '. $request->category_name .' Category added and ' . $request->product_name . ' product added successfully']);
            }
            else{
                $brand = brand::where(['brand_name'=>$request->brand_name])->first();
                $categories = Category::where(['brand_id'=>$brand->id])->where(['category_name'=>$request->category_name])->exists();
                if(empty($categories)){
                    $filename = date('YmdHis') .'.' . $request->image->extension();
                    $request->image->move(public_path('product/icons/'), $filename);
                    $category = new Category;
                    $category->brand_id = $brand->id;
                    $category->category_name = $request->category_name;
                    $category->save();
                    $imagepath = $filename;
                    $product = new products;
                    $product->brand_id = $brand->id;
                    $product->category_id = $category->id;
                    $product->product_name = $request->product_name;
                    $product->product_image = $imagepath;
                    $product->product_link = $request->product_link != "" ? $request->product_link : null;
                    $product->product_mrp = $request->product_mrp;
                    $product->product_discount = $request->product_discount;
                    $product->stock_quantity = $request->stock_quantity;
                    $product->save();
                    
                    return response()->json(['success'=> $request->category_name .' Category Created and ' . $request->product_name . ' product added in '.$brand->brand_name.' successfully']);
       
                }else{
                    $category = Category::where(['brand_id'=>$brand->id])->where(['category_name'=>$request->category_name])->first();
                    $product = products::where(['brand_id'=>$brand->id])->where(['category_id'=>$category->id])->where(['product_name'=>$request->product_name])->exists();
                    if(empty($product)){
                        $filename = date('YmdHis') .'.' . $request->image->extension();
                        $request->image->move(public_path('product/icons/'), $filename);
                        $imagepath = $filename;
                        $product = new products;
                        $product->brand_id = $brand->id;
                        $product->category_id = $category->id;
                        $product->product_name = $request->product_name;
                        $product->product_image = $imagepath;
                        $product->product_link = $request->product_link != "" ? $request->product_link : null;
                        $product->product_mrp = $request->product_mrp;
                        $product->product_discount = $request->product_discount;
                        $product->stock_quantity = $request->stock_quantity;
                        $product->save();
                        return response()->json(['success'=> $request->product_name . ' added successfully']);
                    }
                    else{
                        return response()->json(['error'=>'This '. $request->product_name .' product is already exists in this ' . $request->brand_name . ' Brand category ' .$category->category_name ]);
                    }
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

    function view_product(Request $request) {
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|string|min:1|max:5',
        ]);
           
        try{
            if($validate->fails()){
                return response()->json($validate->errors()->toArray());
            }
            else{
                $data = products::join('brands','brands.id','=','products.brand_id')->join('categories','categories.id','=','products.category_id')->get(['products.id','brands.brand_name','categories.category_name','products.product_name','products.product_image','products.product_link','products.product_mrp','products.product_discount','products.stock_quantity']);
                if($data != ""){
                    return response()->json($data);
                }
                else {
                    return response()->json('Products not Found');
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
    
    function update_product(Request $request) {
        $validate = Validator::make($request->all(), [
            'brand_name' => 'required|string|min:3|max:30',
            'category_name' => 'required|string|min:3|max:30',
            'product_name' => 'required|string|min:3|max:30',
            'image' => 'mimes:jpg,jpeg,bmp,png,svg|image|required|max:2100',
            'product_mrp' => 'required|string|max:10',
            'product_discount' => 'required|string|max:10',
            'stock_quantity' => 'required|string|max:10',
        ]);
        try{
            $brand = brand::where(['brand_name'=>$request->brand_name])->first();
            if($validate->fails()){
                return response()->json($validate->errors()->toArray());
            }
            else if(empty($brand)) {
                     
                $filename = date('YmdHis') .'.' . $request->image->extension();
                $request->image->move(public_path('product/icons/'), $filename);
               
                $brand = new brand;
                $brand->brand_name = $request->brand_name;
                $brand->save();
                $category = new Category;
                $category->brand_id = $brand->id;
                $category->category_name = $request->category_name;
                $category->save();
                $imagepath = $filename;
                $product = new products;
                $product->brand_id = $brand->id;
                $product->category_id = $category->id;
                $product->product_name = $request->product_name;
                $product->product_image = $imagepath;
                $product->product_link = $request->product_link != "" ? $request->product_link : null;
                $product->product_mrp = $request->product_mrp;
                $product->product_discount = $request->product_discount;
                $product->stock_quantity = $request->stock_quantity;
                $product->save();
                
                return response()->json(['success'=> $request->brand_name.' Brand Created, '. $request->category_name .' Category added and ' . $request->product_name . ' product added successfully']);
            }
            else{              
                $brand = brand::where(['brand_name'=>$request->brand_name])->first();
                $categories = Category::where(['brand_id'=>$brand->id])->where(['category_name'=>$request->category_name])->exists();
                if(empty($categories)){
                    $filename = date('YmdHis') .'.' . $request->image->extension();
                    $request->image->move(public_path('product/icons/'), $filename);
                    $category = new Category;
                    $category->brand_id = $brand->id;
                    $category->category_name = $request->category_name;
                    $category->save();
                    $imagepath = $filename;
                    $product = new products;
                    $product->brand_id = $brand->id;
                    $product->category_id = $category->id;
                    $product->product_name = $request->product_name;
                    $product->product_image = $imagepath;
                    $product->product_link = $request->product_link != "" ? $request->product_link : null;
                    $product->product_mrp = $request->product_mrp;
                    $product->product_discount = $request->product_discount;
                    $product->stock_quantity = $request->stock_quantity;
                    $product->save();
                    
                    return response()->json(['success'=> $request->category_name .' Category Created and ' . $request->product_name . ' product added in '.$brand->brand_name.' successfully']);
       
                }else{
                    $category = Category::where(['brand_id'=>$brand->id])->where(['category_name'=>$request->category_name])->first();
                    $product = products::where(['brand_id'=>$brand->id])->where(['category_id'=>$category->id])->where(['product_name'=>$request->product_name])->exists();
                    if(empty($product)){
                        $filename = date('YmdHis') .'.' . $request->image->extension();
                        $request->image->move(public_path('product/icons/'), $filename);
                        $imagepath = $filename;
                        $product = new products;
                        $product->brand_id = $brand->id;
                        $product->category_id = $category->id;
                        $product->product_name = $request->product_name;
                        $product->product_image = $imagepath;
                        $product->product_link = $request->product_link != "" ? $request->product_link : null;
                        $product->product_mrp = $request->product_mrp;
                        $product->product_discount = $request->product_discount;
                        $product->stock_quantity = $request->stock_quantity;
                        $product->save();
                        return response()->json(['success'=> $request->product_name . ' added successfully']);
                    }
                    else{
                        $filename = date('YmdHis') .'.' . $request->image->extension();
                        $request->image->move(public_path('product/icons/'), $filename);
                        $imagepath = $filename;
                        $product = new products;
                        $product->brand_id = $brand->id;
                        $product->category_id = $category->id;
                        $product->product_name = $product->product_name;
                        $product->product_image = $imagepath;
                        $product->product_link = $request->product_link != "" ? $request->product_link : null;
                        $product->product_mrp = $request->product_mrp;
                        $product->product_discount = $request->product_discount;
                        $product->stock_quantity = $request->stock_quantity;
                        $product->save();
                        return response()->json(['success'=> $request->product_name . ' updated successfully']);
                    }
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

    function remove_product (Request $request){
        $validate = Validator::make($request->all(), [
            'user_id' => 'required|string|min:1|max:5',
            'product_id' => 'required|string|min:1|max:5',
        ]);
        try{
            if($validate->fails()){
                return response()->json($validate->errors()->toArray());
            }
            else{
                if(!empty(users::where(['id'=>$request->user_id])->exists())){
                    if(!empty(products::where(['id'=>$request->product_id])->exists())){
                        products::where(['id'=>$request->product_id])->delete();
                        return response()->json(['success'=> 'Product successfully removed']);
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
}
?>