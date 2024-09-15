<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $data;


    /**
     * Create a new event instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
    }
}
