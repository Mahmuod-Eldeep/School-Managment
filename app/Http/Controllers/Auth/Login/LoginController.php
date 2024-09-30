<?php

namespace App\Http\Controllers\Auth\Login;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Authentication"},
     *     summary="Logs user into system",
     *     description="You Can Use this Email => [Mahmuodislam@gmail.com] For Login As a Manager And This Password => [Passwo@rd]",
     *     operationId="loginUser",
     *     @OA\RequestBody(
     *         description="User login credentials",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string",
     *                     format="email",
     *                     example="user@example.com",
     *                     description="User's email address"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string",
     *                     format="password",
     *                     example="password",
     *                     description="User's password"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="access_token", type="string", description="JWT access token"),
     *             @OA\Property(property="token_type", type="string", description="Token type")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid email/password supplied"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function login(LoginRequest $loginRequest)
    {
        $credentials = $loginRequest->only('email', 'password');
        if (Auth::attempt($credentials)) {
            $user = User::where('email', $loginRequest->email)->first();

            return response()->json([
                'access_token' => $user->createToken('api_token')->plainTextToken,
                'token_type' => 'Bearer'
            ]);
        }

        return response()->json([
            'message' => 'Login information invalid'
        ], 401);
    }
}
