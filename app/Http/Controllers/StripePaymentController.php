<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // استدعاء الفصل Auth
use Stripe\Stripe;

class StripePaymentController extends Controller
{
    public function stripePost(Request $request)
    {
        try {
            // التحقق من أن المستخدم قد قام بتسجيل الدخول
            if (Auth::check()) {
                // تعيين مفتاح API الخاص بـ Stripe
                Stripe::setApiKey(env('STRIPE_SECRET'));

                // استخدام رمز اختباري للبطاقة (استبدال 'tok_test...' برمز اختباري فعلي)
                $token = $request->input('token');

                // إنشاء عملية شحن باستخدام الرمز الاختباري
                $charge = \Stripe\Charge::create([
                    'amount' => $request->amount,
                    'currency' => 'usd',
                    'source' => $token,
                    'description' => $request->description,
                ]);

                // إذا كانت عملية الشحن ناجحة، قم بتحديث حالة الدفع للمستخدم من false إلى true
                if ($charge->status === 'succeeded') {
                    // قم بتحديث حالة الدفع للمستخدم
                    auth()->user()->update(['payment_status' => true]);
                }

                // إرجاع رد ناجح
                return response()->json(['status' => $charge->status], 201);
            } else {
                // إذا لم يكن المستخدم قد قام بتسجيل الدخول
                return response()->json(['error' => 'User not logged in.'], 401);
            }
        } catch (Exception $ex) {
            // إرجاع رد خطأ مع رسالة الخطأ الفعلية
            return response()->json(['error' => $ex->getMessage()], 500);
        }
    }
}




//     public function stripePost(Request $request)
//     {
//         try {

//             $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));


//             $res = $stripe->tokens->create([
//                 'card' => [
//                     'number' => $request->number,
//                     'exp_month' => $request->exp_month,
//                     'exp_year' => $request->exp_year,
//                     'cvc' => $request->cvc,
//                 ],
//             ]);

//             Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

//             $response = $stripe->charges->create([
//                 'amount' => $request->amount,
//                 'currency' => 'usd',
//                 'source' => $res->id,
//                 'description' => $request->description,


//             ]);


//             return response()->json([$response->status], 201);
//         } catch (Exception $ex) {
//             return response()->json([['response ' => 'Error']], 500);
//         }
//     }
// }
