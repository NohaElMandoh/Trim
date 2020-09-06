<?php

namespace Modules\Order\Entities;

use League\Fractal;
use League\Fractal\Manager;
use Modules\Order\Entities\Message;

class MessageTransformer extends Fractal\TransformerAbstract
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }

    public function transform(Message $message)
    {
        return [
            'id'            => (int) $message->id,
            'user_id'       => $message->user_id,
            'type'          => $message->type,
            'message'       => $message->type == 'text' ? $message->message: route('file_show', $message->message),
        ];
    }
}
