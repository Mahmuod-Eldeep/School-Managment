<?php

namespace App\Http\Controllers\Auth\RestPassword;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Annotations as OA;

class RestController extends Controller
{

    public function rest(RegisterRequest $registerRequest)
    {
        $userData = $registerRequest->only(['name', 'email', 'password', 'phoneNumber', 'classRoom', 'status']);
        $userData['password'] = Hash::make($registerRequest['password']);

        $user = User::create($userData);

        if ($registerRequest['status'] === 'Student') {
            $user->Payment()->create();
        }

        return response()->json([
            'data' => new UserResource($user),
            // 'access_token' => $user->createToken('api_token')->plainTextToken,
            // 'token_type' => 'Bearer'
        ], 201);
    }
}
