<?php

namespace App\Listeners;

use App\Events\PaymentEvent;
use App\Mail\PaymentConfirmationMail;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class PaymentComfirmation
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PaymentEvent $event): void
    {
        $data = $event->data;
        $user = User::find($data->CustomerReference);


        Mail::to($user->email)->send(new PaymentConfirmationMail($data, $user));
    }
}
