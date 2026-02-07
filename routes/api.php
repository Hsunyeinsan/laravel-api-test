<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\PhotoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TempController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Sanctum\Sanctum;

Route::get("/",fn()=>response()->json([
    "message","api is wprking"
]));

Route::controller(AuthController::class)->group(function(){
    Route::post("/register","register");
    Route::post("/login","login");
});

// Route::prefix("dashboard")->middleware('auth:sanctum')->group(function(){
//     Route::apiResource("/customer",CustomerController::class);
// });

Route::middleware('auth:sanctum')->prefix('dashboard')->group(function(){
    Route::controller(ProfileController::class)->prefix('user-profile')->group(function (){
        Route::get('/show','show');
        Route::patch('/logout','logout');
        Route::patch('/change_password','changePassword');
        Route::patch('/change_name','changeName');
    });
    Route::apiResource("/customer",CustomerController::class);
    Route::apiResource("/photo",PhotoController::class)->only('store','destroy');

});

Route::controller(TempController::class)->prefix('temp')->group(function(){
    Route::get("/","index");
    Route::get("/destroy","destroy");
});

