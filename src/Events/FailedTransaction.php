<?php

namespace ZakariaTlilani\PayFort\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FailedTransaction
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $payment_transaction;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(array $payment_transaction)
    {
        $this->payment_transaction = $payment_transaction;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
