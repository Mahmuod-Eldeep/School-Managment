<?php

namespace App\Http\Controllers;

use App\Mail\PaymentSuccessNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class emailcontroller extends Controller
{
    public function send()
    {
        $mail = new PaymentSuccessNotification(Auth::user());
        Mail::to(Auth::user()->email)->send($mail);
        return  response("Email Sent Sucessfully");
    }
}
