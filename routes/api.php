<?php

use App\Http\Controllers\SubjectController;
use  App\Http\Controllers\TaskController;
use  App\Http\Controllers\AuthController;
use App\Http\Controllers\emailcontroller;
use App\Http\Controllers\MyFatoorahController;
use App\Http\Controllers\StripePaymentController;
use App\Http\Controllers\UserController;
use App\Mail\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  Illuminate\Support\Facades\Mail;

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

Route::post("login", [AuthController::class, 'login']);
Route::post("forgot_password", [AuthController::class, 'forgot_password']);
Route::post("rest/{token}", [AuthController::class, 'rest']);

//- - - - - - - -- - - - - - - - - - - -- - - - - - - - - -- - - - - - - - - - - - -- - - - - - - - - -
//Route::post("register", [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post("register", [AuthController::class, 'register']);
//- - - - - - - -- - - - - - - - - - - -- - - - - - - - - -- - - - - - - - - - - - -- - - - - - - - - -
Route::middleware('auth:sanctum')->post("logout", [AuthController::class, 'logout']);
//- - - - - - - -- - - - - - - - - - - -- - - - - - - - - -- - - - - - - - - - - - -- - - - - - - - - -
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('subjects', SubjectController::class);
    Route::apiResource('users', UserController::class);
});
//- - - - - - - -- - - - - - - - - - - -- - - - - - - - - -- - - - - - - - - - - - -- - - - - - - - - -
Route::middleware('auth:sanctum')->post("stripe", [StripePaymentController::class, 'stripePost']);
//- - - - - - - -- - - - - - - - - - - -- - - - - - - - - -- - - - - - - - - - - - -- - - - - - - - - -
Route::middleware('auth:sanctum')->post("Myfatoora", [MyFatoorahController::class, 'index']);
//- - - - - - - -- - - - - - - - - - - -- - - - - - - - - -- - - - - - - - - - - - -- - - - - - - - - -
