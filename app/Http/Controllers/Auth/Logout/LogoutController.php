<?php

namespace App\Http\Controllers\Auth\Logout;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/logout",
     *     tags={"Authentication"},
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
