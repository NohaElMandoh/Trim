<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
use Modules\Status\Entities\Status;

class ApiOrderController extends Controller
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
        if (isset($_GET['include'])) {
            $this->fractal->parseIncludes($_GET['include']);
        }
    }

    public function currentUserOrders()
    {
        $paginator  = Order::where('user_id', auth()->id())->whereHas('status', function ($query) {
            $query->where('slug', '!=', 'cancelled')->orWhere('slug', '!=', 'delivered');
        })->latest()->paginate(10);
        $orders = $paginator->getCollection();
        $resource = new Collection($orders, new OrderTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return response_api($this->fractal->createData($resource)->toArray());
    }

    public function cancelledUserOrders()
    {
        $paginator  = Order::where('user_id', auth()->id())->whereHas('status', function ($query) {
            $query->where('slug', 'cancelled');
        })->latest()->paginate(10);
        $orders = $paginator->getCollection();
        $resource = new Collection($orders, new OrderTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return response_api($this->fractal->createData($resource)->toArray());
    }

    public function deliveredUserOrders()
    {
        $paginator  = Order::where('user_id', auth()->id())->whereHas('status', function ($query) {
            $query->where('slug', 'delivered');
        })->latest()->paginate(10);
        $orders = $paginator->getCollection();
        $resource = new Collection($orders, new OrderTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return response_api($this->fractal->createData($resource)->toArray());
    }

    public function getMessages(Request $request)
    {
        $paginator  = Message::where('order_id', $request->order_id)->latest()->paginate(10);
        $messages = $paginator->getCollection();
        $resource = new Collection($messages, new MessageTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return response_api($this->fractal->createData($resource)->toArray());
    }

    public function sendMessage(Request $request)
    {
        $validation = [
            'order_id'  => 'required|exists:orders,id',
            'type'      => 'required|in:text,image',
        ];
        if ($request->type == 'text') {
            $validation['message'] = 'required|string';
        } else {
            $validation['message'] = 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048';
        }
        $validator = Validator::make($request->all(), $validation);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
        }
        $data               = $request->all();
        if($request->type == 'image') {
            $data['message'] = upload_file($request, 'message');
        }
        $data['user_id']    = auth()->id();
        $message = Message::create($data);
        event(new NewMessageEvent($message));
        $resource = new Item($message, new MessageTransformer);
        return response_api($this->fractal->createData($resource)->toArray(), true, __('messages.Message sent successfully'));
    }
    public function financials (Request $request) {
        $validation = [
            'from'  => 'required|date',
            'to'    => 'required|date',
        ];
        $validator = Validator::make($request->all(), $validation);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
        }
        $delivered = Status::where('slug', 'delivered')->firstOrFail();
        $orders = Order::where('status_id', $delivered->id)
                    ->whereNull('user_id')
                    ->where('shop_id', auth()->id())
                    ->whereDate('created_at', '>=', $request->from)
                    ->whereDate('created_at', '<=', $request->to)
                    ->latest()
                    ->get();
        $resource = new Collection($orders, new OrderTransformer);
        return response_api($this->fractal->createData($resource)->toArray());
    }
}
