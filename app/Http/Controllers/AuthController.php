<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * @OA\Schema(
 *     schema="User",
 *     title="User",
 *     description="User data",
 *     @OA\Property(property="name", type="string", example="Mahmuod Eldeep"),
 *     @OA\Property(property="email", type="string", format="email", example="user@example.com"),
 *     @OA\Property(property="password", type="string", format="password", example="password"),
 *     @OA\Property(property="status", type="string", enum={"Manager", "Employee"}, example="Manager")
 * )
 *
 */

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     tags={"Login"},
     *     summary="Logs user into system",
     *  description="You Can Use this Email => [Mahmuodislam@gmail.com] For Login As a Manager And This Password [password]",
     *     operationId="loginUser",
     *     @OA\RequestBody(
     *         description="User login credentials",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\Header(
     *             header="X-Rate-Limit",
     *             description="calls per hour allowed by the user",
     *             @OA\Schema(
     *                 type="integer",
     *                 format="int32"
     *             )
     *         ),
     *         @OA\Header(
     *             header="X-Expires-After",
     *             description="date in UTC when token expires",
     *             @OA\Schema(
     *                 type="string",
     *                 format="datetime"
     *             )
     *         ),
     *         @OA\JsonContent(
     *             type="string"
     *         ),
     *
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid email/password supplied"
     *     )
     * )
     */
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

    /**
     * @OA\Post(
     *     path="/api/register",
     *     tags={"Crate a New User"},
     *     summary="Registers a new user",
     * description="Only managers can create an account for students and teachers",
     *     operationId="registerUser",
     *     security={{"BearerAuth": {}}},
     *     @OA\RequestBody(
     *         description="User registration data",
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password", "password_confirmation", "status"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="password"),
     *             @OA\Property(property="status", type="string", enum={"Manager", "Employee"}, example="Manager")
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




    public function register(Request $request)
    {

        if (Auth::user()->status !== 'Manager') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }
        $validate = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|max:255|email|unique:users,email',
            'phoneNumber' => 'required|max:50',
            "classRoom" => 'required',
            'password' => 'required | confirmed|min:6',
            'status' => 'required'

        ]);
        $validate['password'] = Hash::make($validate['password']);


        $user = User::create($validate);


        if ($validate['status'] === 'Student') {
            $user->Payment()->create();
        }




        return response()->json([
            'data' => $user,
            // 'acces_token' => $user->createToken('api_token')->plainTextToken,
            // 'token_type' => 'Bearer'
        ], 201);
    }
    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Logout"},
     *     summary="Logs out the currently authenticated user",
     *     operationId="logoutUser",
     *     security={{"BearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Logged out successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */




    public function logout(Request $request)
    {
        if (Auth::check()) {
            $request->user()->tokens()->delete(); // حذف جميع الـ Tokens المرتبطة بالمستخدم الحالي
        }

        Auth::guard('web')->logout(); // تسجيل الخروج من الـ Session


        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
}
