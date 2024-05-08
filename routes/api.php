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
//------------------------------------------------------------------------------------
Route::post("login", [AuthController::class, 'login']);
//------------------------------------------------------------------------------------
// Route::post("register", [AuthController::class, 'register']);
//------------------------------------------------------------------------------------
Route::middleware('auth:sanctum')->post("register", [AuthController::class, 'register']);
//------------------------------------------------------------------------------------
Route::middleware('auth:sanctum')->post("logout", [AuthController::class, 'logout']);
//------------------------------------------------------------------------------------
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });
//------------------------------------------------------------------------------------
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', TaskController::class);
    Route::apiResource('subjects', SubjectController::class);
});
//-----------------------------------------------------STRIPE_ROUTE_SYSTEM-------------
Route::middleware('auth:sanctum')->post("stripe", [StripePaymentController::class, 'stripePost']);
// Route::post('stripe', [StripePaymentController::class, 'stripePost']);
//------------------------------------------------------------------------------------


//-----------------------------------------------------My_Fatoorah-------------------------------
Route::middleware('auth:sanctum')->post("Myfatoora", [MyFatoorahController::class, 'index']);
// Route::post('Myfatoora', [MyFatoorahController::class, 'index']);
//-----------------------------------------------------________-------------------------------
// Route::get('sendEmail', action: function () {
//     Mail::to(users: 'mahmuodeldeep114@gmail.com')->send(new Email());
//     return "Email Send";
// });
//------------------------------------------------------------------------------------------

// Route::middleware('auth:api')->prefix('users')->group(function () {
//     Route::get('show/{id}', [UserController::class, 'show']);
//     Route::put('update/{id}', [UserController::class, 'update']);
//     Route::delete('delete/{id}', [UserController::class, 'destroy']);
//     Route::post('Store', [UserController::class, 'store']);
// });
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
});
// Route::get('user/{id}', [UserController::class, 'show']);
