<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\OrderTransformer;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Item;
use League\Fractal\Resource\Collection;
use Modules\Order\Entities\Message;
use Modules\Order\Entities\MessageTransformer;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\Validator;
use Modules\Order\Events\NewMessageEvent;


class ApiOrderShopController extends Controller
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }

    public function currentShopOrders()
    {
        $paginator  = Order::whereNull('user_id')
        ->where('shop_id', auth()->id())
        ->whereHas('status', function ($query) {
            $query->where('slug', '!=', 'cancelled')->orWhere('slug', '!=', 'delivered');
        })->latest()->paginate(10);
        $orders = $paginator->getCollection();
        $resource = new Collection($orders, new OrderTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return response_api($this->fractal->createData($resource)->toArray());
    }

    public function cancelledShopOrders()
    {
        $paginator  = Order::whereNull('user_id')
        ->where('shop_id', auth()->id())->whereHas('status', function ($query) {
            $query->where('slug', 'cancelled');
        })->latest()->paginate(10);
        $orders = $paginator->getCollection();
        $resource = new Collection($orders, new OrderTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return response_api($this->fractal->createData($resource)->toArray());
    }

    public function deliveredShopOrders()
    {
        $paginator  = Order::whereNull('user_id')
        ->where('shop_id', auth()->id())->whereHas('status', function ($query) {
            $query->where('slug', 'delivered');
        })->latest()->paginate(10);
        $orders = $paginator->getCollection();
        $resource = new Collection($orders, new OrderTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return response_api($this->fractal->createData($resource)->toArray());
    }
}
