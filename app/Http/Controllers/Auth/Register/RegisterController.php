<?php

namespace App\Http\Controllers\Auth\Register;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Components(
 *     @OA\Schema(
 *         schema="User",
 *         title="User",
 *         description="User data",
 *         @OA\Property(property="name", type="string", example="Mahmuod Eldeep"),
 *         @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *         @OA\Property(property="password", type="string", format="password", example="password"),
 *         @OA\Property(property="status", type="string", enum={"Manager", "Employee"}, example="Manager")
 *     )
 * )
 */

class RegisterController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Authentication"},
     *     summary="Registers a new user",
     *     description="Only managers can create an account for students and teachers. Use the API Key.",
     *     operationId="registerUser",
     *     @OA\Parameter(
     *         name="api_key",
     *         in="query",
     *         required=true,
     *         description="API key for authentication",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         description="User registration data",
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "email", "password", "password_confirmation", "status"},
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *                 @OA\Property(property="password", type="string", format="password", example="password"),
     *                 @OA\Property(property="password_confirmation", type="string", format="password", example="password"),
     *                 @OA\Property(property="phoneNumber", type="string", example="123456789"),
     *                 @OA\Property(property="classRoom", type="string", example="Class A"),
     *                 @OA\Property(property="status", type="string", enum={"Manager", "Teacher", "Student"}, example="Manager")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object", ref="#/components/schemas/User"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *     )
     * )
     */

    public function register(RegisterRequest $registerRequest)
    {
        $userData = $registerRequest->only(['name', 'email', 'password', 'phoneNumber', 'classRoom', 'status']);
        $userData['password'] = Hash::make($registerRequest['password']);

        $user = User::create($userData);

        if ($registerRequest['status'] === 'Student') {
            $user->Payment()->create();
        }

        return response()->json([
            'data' => new UserResource($user),
            // 'acces_token' => $user->createToken('api_token')->plainTextToken,
            // 'token_type' => 'Bearer'
        ], 201);
    }
}
