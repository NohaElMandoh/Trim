<?php

namespace App;

use League\Fractal;

class NotificationTransformer extends Fractal\TransformerAbstract
{

    public function transform($notification)
    {
        return [
            'id'            => $notification->id,
            'type'          => $notification->type,
            'data'          => $notification->data,
            'read_at'       => $notification->read_at,
            'created_at'    => date('Y-m-d h:i A', strtotime($notification->created_at)),
        ];
    }
}
