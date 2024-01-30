<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\DrinkController;
use App\Http\Controllers\api\TypeController;
use App\Http\Controllers\api\QuantityController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([ "middleware" => [ "auth:sanctum" ]], function() {

	Route::post( "/logout", [ AuthController::class, "logOut" ]);
	Route::post( "/addDrink", [ DrinkController::class, "addDrink" ]);
    	Route::put( "/updatedrink/{id}", [ DrinkController::class, "updateDrink" ]);
    	Route::delete( "/deletedrink/{id}", [ DrinkController::class, "deleteDrink" ]);
	Route::post( "/addtype", [ TypeController::class, "addType" ]);
	Route::put( "/updatetype/{id}", [ TypeController::class, "updateType" ]);
	Route::delete( "/deletetype/{id}", [ TypeController::class, "deleteType" ]);
	Route::post( "/addquantity", [ QuantityController::class, "addQuantity" ]);
	Route::put( "updatequantity/{id}", [ QuantityController::class, "updateQuantity" ]);
	Route::delete( "/deletequantity/{id}", [ QuantityController::class, "deleteQuantity" ]);
});

Route::post( "/register", [ AuthController::class, "register" ]);
Route::post( "/login", [ AuthController::class, "login" ]);
Route::get( "/drinks", [ DrinkController::class, "getDrinks" ]);
Route::get( "/oneDrink/{id}", [ DrinkController::class, "getOneDrink" ]);
Route::get( "/types", [ TypeController::class, "getTypes" ]);
Route::get( "/quantities", [ QuantityController::class, "getQuantities" ]);

