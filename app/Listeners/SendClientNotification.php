<?php

namespace App\Listeners;

use App\Events\clientnotify;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendClientNotification
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  clientnotify  $event
     * @return void
     */
    public function handle(clientnotify $event)
    {
        $order = $event->order;
        $barber    = $event->barber;
        \App\Notification::create([

            'type' => get_class($order),
            'notifiable_type' => get_class($barber),
            'notifiable_id'   => $barber->id,
            'is_read' => 0,
            'data'         => $event->text,

        ]);
    }
}
