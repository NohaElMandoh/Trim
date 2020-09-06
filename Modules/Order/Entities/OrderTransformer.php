<?php

namespace Modules\Order\Entities;

use League\Fractal;
use League\Fractal\Manager;
use Modules\Order\Entities\Order;

class OrderTransformer extends Fractal\TransformerAbstract
{
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        'items', 'delivery_locations', 'captain'
    ];
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }


    public function transform(Order $order)
    {
        return [
            'id'            => (int) $order->id,
            'user_id'       => $order->user_id,
            'type'          => $order->type,
            'payment_method' => $order->payment_method,
            'captain_id'    => $order->captain_id,
            'status_id'     => $order->status_id,
            'shop_name'     => $order->shop_name,
            'shop_id'       => $order->shop_id,
            'created_at'    => date('Y-m-d h:i A', strtotime($order->created_at)),
            'status'        => [
                'id'            => $order->status->id ?? '',
                'name'          => $order->status->name ?? '',
                'slug'          => $order->status->slug ?? '',
            ],
        ];
    }

    /**
     * Include Items
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeItems($order)
    {
        $items = $order->items()->get();
        return $this->collection($items, function ($item) {
            return [
                'name'      => $item->name,
                'qty'       => $item->qty,
                'price'     => $item->price,
            ];
        });
    }

    /**     
     * Include Delivery Locations
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeDeliveryLocations($order)
    {
        $delivery_locations = $order->delivery_locations()->get();        
        return $this->collection($delivery_locations, function ($delivery_location) {
            return [
                'name'      => $delivery_location->name,
                'items'     => $delivery_location->items
            ];
        });
    }

    /**
     * Include Captain
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function includeCaptain($order)
    {
        $captain = $order->captain()->first();
        return $this->item($captain, function ($captain) {
            return [
                'name'      => $captain->name,
            ];
        });
    }
}
