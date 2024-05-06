<?php

use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;
use App\Mail\Email;
use  Illuminate\Support\Facades\Mail;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



// //|--------------------------------------------------------------------------
// Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect']);
// //|--------------------------------------------------------------------------
// Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);
// //|--------------------------------------------------------------------------
Route::get('/', function () {
    return view('welcome');
});

Route::get(uri: '/send', action: function () {
    Mail::to(users: 'khaldsyrk@gmail.com')->send(new Email());
    return "Email Send!";
});

//|--------------------------------------------------------------------------
