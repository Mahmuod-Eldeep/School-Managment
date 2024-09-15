<?php

namespace App\Listeners;

use App\Events\PaymentEvent;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateStudentStatus
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

        $user->update([
            'payment_status' => 'Paid'
        ]);
        $user->save();
    }
}
