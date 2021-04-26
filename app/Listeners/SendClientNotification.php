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
        $user    = auth()->user();
        \App\Notification::create([

            'type' => get_class($order),
            'notifiable_type' => get_class($user),
            'notifiable_id'   => $user->id,
            'is_read' => 0,
            'data'         => $event->text,

        ]);
    }
}
