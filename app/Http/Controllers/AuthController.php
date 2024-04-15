<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        $validated = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],

        ]);

        if (Auth::attempt($validated)) {

            $user = User::where('email', $validated['email'])->first();
            return response()->json([
                'acces_token' => $user->createToken('api_token')->plainTextToken,
                'token_type' => 'Bearer'
            ]);
        }
        return response()->json([
            'message' => 'Login information invalid'
        ], 401);
    }

    public function register(Request $request)
    {
        // التحقق مما إذا كان المستخدم الحالي يمتلك الصلاحية لإنشاء حساب
        if (Auth::user()->status !== 'Manager') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        $validate = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255|email|unique:users,email',
            'password' => 'required | confirmed|min:6',
            'status' => 'required'

        ]);
        $validate['password'] = Hash::make($validate['password']);

        $user = User::create($validate);


        return response()->json([
            'data' => $user,
            // 'acces_token' => $user->createToken('api_token')->plainTextToken,
            // 'token_type' => 'Bearer'
        ], 201);
    }
}
