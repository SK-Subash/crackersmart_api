<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;

Route::get('/', function () {
    return "Hello!";
    return view('welcome');
});

Route::get('Home', function () {
    return view('index');
});

Route::post('signup',[UserController::class,'signup']);
Route::post('verify_otp',[UserController::class,'verify_otp']);
Route::post('login',[UserController::class,'login']);
Route::post('update_user',[UserController::class,'update_user']);
Route::post('reset_password',[UserController::class,'reset_password']);
Route::post('add_product',[ProductController::class,'add_product']);
Route::post('update_product',[ProductController::class,'update_product']);
Route::get('view_product',[ProductController::class,'view_product']);
Route::post('remove_product',[ProductController::class,'remove_product']);
Route::post('add_cart',[CartController::class,'add_cart']);
Route::post('add_quantity',[CartController::class,'add_quantity']);
Route::post('remove_cart',[CartController::class,'remove_cart']);
Route::get('view_cart',[CartController::class,'view_cart']);
Route::any('order_details',[OrderController::class,'order_details']);
Route::any('orders',[OrderController::class,'orders']);