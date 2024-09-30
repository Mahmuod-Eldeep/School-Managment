<?php

use App\Http\Controllers\Auth\ForgotPassword\ForgotPasswordController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Auth\Login\LoginController;
use App\Http\Controllers\Auth\Logout\LogoutController;
use App\Http\Controllers\Auth\Register\RegisterController;
use App\Http\Controllers\Auth\RestPassword\RestController;
use App\Http\Controllers\MyFatoorahController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;






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

//- - - - - - - -- - - - - - - - - - - -- - - - - - - - - -- - - - - - - - - - - - -- - - - - - - - - -
Route::post("forgot_password", [ForgotPasswordController::class, 'forgot_password']);
//- - - - - - - -- - - - - - - - - - - -- - - - - - - - - -- - - - - - - - - - - - -- - - - - - - - - -
Route::post("restpassowrd/{token}", [RestController::class, 'rest']);
//- - - - - - - -- - - - - - - - - - - -- - - - - - - - - -- - - - - - - - - - - - -- - - - - - - - - -
Route::post("login", [LoginController::class, 'login']);
//- - - - - - - -- - - - - - - - - - - -- - - - - - - - - -- - - - - - - - - - - - -- - - - - - - - - -
Route::post("register", [RegisterController::class, 'register'])->middleware('check.apikey');
//- - - - - - - -- - - - - - - - - - - -- - - - - - - - - -- - - - - - - - - - - - -- - - - - - - - - -
Route::middleware('auth:sanctum')->post("logout", [LogoutController::class, 'logout']);
//- - - - - - - -- - - - - - - - - - - -- - - - - - - - - -- - - - - - - - - - - - -- - - - - - - - - -
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('subjects', SubjectController::class);
    Route::apiResource('users', UserController::class);
});
// //- - - - - - - -- - - - - - - - - - - -- - - - - - - - - -- - - - - - - - - - - - -- - - - - - - - - -
Route::middleware('auth:sanctum')->post("stripe", [StripePaymentController::class, 'stripePost']);
// //- - - - - - - -- - - - - - - - - - - -- - - - - - - - - -- - - - - - - - - - - - -- - - - - - - - - -
Route::middleware('auth:sanctum')->post("Myfatoora", [MyFatoorahController::class, 'index']);
// //- - - - - - - -- - - - - - - - - - - -- - - - - - - - - -- - - - - - - - - - - - -- - - - - - - - - -
