<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return  Socialite::driver("google")->redirect();
    }
    public function callback()
    {
        $googleuser = Socialite::driver("google")->user();
        $user =  User::updateOrCreate(
            ['google_id' => $googleuser->id],
            [
                'name' => $googleuser->getName(),
                'email' => $googleuser->getEmail(),
                'password' => Hash::make('my-google'),
            ]
        );
        $token = $user->createToken('Personal Access Token')->plainTextToken;
        Auth::login($user);

        return response()->json([
            'Data' => $user,
            'token' => $token, // إرجاع رمز المميز
            'message' => 'Login Successfully'
        ], 200);
    }
}
