<?php

namespace App\Http\Controllers\Api\salon;

use App\Events\clientnotify;
use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ServiceResource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use App\Token;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use App\NotificationTransformer;
use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Modules\Offer\Entities\Offer;
use Modules\Order\Entities\Order;
use Modules\Service\Entities\Service;
use Modules\Status\Entities\Status;
use Throwable;


class OrderController extends Controller
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }


    public function orders(Request $request)
    {
        if($request->has('day'))
        $orders = $request->user()->shop_orders()->where('reservation_day',$request->day)->paginate(10);
        else
        $orders = $request->user()->shop_orders()->paginate(10);
       return response()->json(['success' => true, 'data' =>OrderResource::collection($orders)->response()->getData(true) ], 200);
       
    }
    public function order(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'order_id' => 'required',

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }
        $order = Order::find($request->order_id);
        if ($order)
            return response()->json(['success' => true, 'data' => new  OrderResource($order)], 200);
        else
            return response()->json(['success' => false, 'message' => __('messages.Order Not Exist')], 400);
    }
    public function cancelOrder(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'order_id' => 'required',
       

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }
        $cancelStatus = Status::where('slug', 'cancelled')->first();
        $order = Order::find($request->order_id);
     
        if ($order) {
          if( !empty( $request->user()->shop_orders()->where('id',$request->order_id)->first())){
            $order->update([
                'status_id' => $cancelStatus->id,
         
            ]);
          } else return response()->json(['success' => false, 'message' => __('messages.Order Does Not Belongs To You')], 400);
        } else return response()->json(['success' => false, 'message' => __('messages.Order Not Exist')], 400);
        event(new clientnotify($order, 'your order cancelled'));
        if ($order)
            return response()->json(['success' => true, 'data' => new  OrderResource($order)], 200);
        else
            return response()->json(['success' => false, 'message' => __('messages.Try Again Later')], 400);
    }
    public function acceptOrder(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }
        $cancelStatus = Status::where('slug', 'processing')->first();
        $order = Order::find($request->order_id);
     
        if ($order) {
          if( !empty( $request->user()->shop_orders()->where('id',$request->order_id)->first())){
            $order->update([
                'status_id' => $cancelStatus->id,
              
            ]);
          } else return response()->json(['success' => false, 'message' => __('messages.Order Does Not Belongs To You')], 400);
        } else return response()->json(['success' => false, 'message' => __('messages.Order Not Exist')], 400);
        event(new clientnotify($order, 'your order cancelled'));
        if ($order)
            return response()->json(['success' => true, 'data' => new  OrderResource($order)], 200);
        else
            return response()->json(['success' => false, 'message' => __('messages.Try Again Later')], 400);
    }
    public function newOrder(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'products' => 'required|array',
            'products.*.product_id' => 'required',
            'products.*.quantity' => 'required',
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }
        // $barber = User::find($request->barber_id);
        // if ($barber) {
        // 'cost','discount','total',
        $order = $request->user()->orders()->create([
            'address' => $request->address,
            'phone' => $request->phone,
            'payment_method' => $request->payment_method,
            'status_id' => 1,
            'order_type' => 'products'

        ]);
        $cost = 0;
        $discount = 0;
        $total = 0;
        foreach ($request->products as $i) {
            $item = Product::find($i['product_id']);
            if ($item) {
                $readyItem = [
                    $i['product_id'] => [
                        'qty' => $i['quantity'],
                        'price' => $item->price,
                        'total' => $i['quantity'] * $item->price
                    ]
                ];
                $order->products()->attach($readyItem);
            } else return response()->json(['success' => false, 'message' => __('messages.product not exist ')], 400);
        }

        $cost = $order->products()->sum('order_product.total');
        $payment_coupon = "";
        if ($request->has('payment_coupon')) {
            $coupone = Coupon::where('code', $request->payment_coupon)->first();
            ////check if avaliable
            if ($coupone) {
                $user_coupon_ids = $request->user()->coupons->pluck('pivot.coupon_id')->toArray();
                if (in_array($coupone->id, $user_coupon_ids)) {
                    $usages = $request->user()->coupons()->where('coupon_id', $coupone->id)->first();
                    if ($usages->pivot->usage > 0) {
                        $usage = ($usages->pivot->usage) - 1;
                        $request->user()->coupons()->updateExistingPivot($coupone->id, ['usage' =>  $usage]);
                        $discount = $coupone->price;
                        $payment_coupon = $request->payment_coupon;
                    } else return response()->json(['success' => false, 'message' => __('messages.cant use coupone')], 400);
                } else return response()->json(['success' => false, 'message' => __('messages.coupone not in your list')], 400);
            } else  return response()->json(['success' => false, 'message' => __('messages.coupone not avaliable')], 400);
        }

        $total = $cost - $discount;
        if ($total < 0) {
            $total = 0;
        }
        $order->update([
            'cost' => $cost,
            'discount' => $discount,
            'total' => $total,
            'payment_coupon' => $payment_coupon,

        ]);
        $request->user()->cart()->detach();
        if ($order)
            return response()->json(['success' => true, 'data' => new OrderResource($order)], 200);
        else
            return response()->json(['success' => false, 'message' => __('messages.Try Again Later')], 400);
        // } else  return response()->json(['success' => false, 'message' => __('messages.salon not exist')], 400);
    }
}
