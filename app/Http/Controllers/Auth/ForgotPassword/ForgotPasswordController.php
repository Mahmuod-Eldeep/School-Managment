<?php

namespace App\Http\Controllers\Auth\ForgotPassword;

use App\Http\Controllers\Controller;
use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use OpenApi\Annotations as OA;

class ForgotPasswordController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/forgot_password",
     *     tags={"Authentication"},
     *     summary="Send a password reset email",
     *     description="Use the provided email to receive a password reset link.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(
     *                 @OA\Property(property="email", type="string", format="email", description="User email"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password reset email sent successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Please check your email and reset your password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Email not found in the system",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="This Email not found in the system.")
     *         )
     *     )
     * )
     */
    public function forgot_password(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!empty($user)) {
            $user->remember_token = Str::random(40);
            $user->save();
            Mail::to($user->email)->send(new ForgotPasswordMail($user));
            return response()->json(['success' => 'Please check your email and reset your password']);
        } else {
            return response()->json(['error' => 'This Email not found in the system.'], 404);
        }
    }
}
