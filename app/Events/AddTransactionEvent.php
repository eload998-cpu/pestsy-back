<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddTransactionEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $plan_id;
    public $user_id;
    public $status_id;
    public $type;
    public $plan_status_id;
    public $data;
    public $reference;
    public $sentEmail;


    public function __construct($user_id,$plan_id,$status_id,$type,$plan_status_id,$data, $reference = null, $sentEmail = true)
    {
        $this->plan_id = $plan_id;
        $this->user_id = $user_id;
        $this->status_id = $status_id;
        $this->type = $type;
        $this->plan_status_id = $plan_status_id;
        $this->data = $data;
        $this->reference = $reference;
        $this->sentEmail = $sentEmail;

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
