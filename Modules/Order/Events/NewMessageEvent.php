<?php

namespace Modules\Order\Events;

use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Modules\Order\Entities\Message;

class NewMessageEvent implements ShouldBroadcast
{
    use SerializesModels;
    private $message;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Message $message)
    {
        $this->message          = $message;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('Modules.Order.Entities.Order.' . $this->message->order_id);
    }
    public function broadcastWith()
    {
        $return          = ['message' => $this->message->type == 'text' ? $this->message->message : route('file_show', $this->message->message), 'id' => $this->message->id, 'user_id' => $this->message->user_id, 'type' => $this->message->type, 'order_id' => $this->message->order_id];
        return $return;
    }
}
