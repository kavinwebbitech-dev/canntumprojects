<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CommanApiController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\Auth\LoginRegisterController;

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

// Public routes of authtication
Route::controller(LoginRegisterController::class)->group(function() {
    Route::post('/register', 'register');
    Route::post('/login', 'login');
    Route::post('/register-send-otp', 'register_send_otp');
    Route::post('/forget-password','forget_password');
    Route::post('/verification-code','verification_code');
    Route::post('/reset-password','reset_password');
});


Route::middleware('auth:sanctum')->group( function () {
    
    
    Route::post('/logout', [LoginRegisterController::class, 'logout']);
    

    Route::controller(ProductController::class)->group(function() {
        
        
    });
    
    Route::controller(LoginRegisterController::class)->group(function() {
        Route::post('/edit-profile','edit_profile');
        Route::post('/get-profile','getProfile');
    });
    
    Route::controller(CommanApiController::class)->group(function() {
        
        
    });
    
});














