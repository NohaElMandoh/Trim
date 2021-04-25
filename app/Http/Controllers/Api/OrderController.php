<?php

namespace App\Http\Controllers\Api;

use App\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\CouponeResource;
use App\Http\Resources\OfferResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\SalonResource;
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
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Modules\Coupon\Entities\Coupon;
use Modules\Offer\Entities\Offer;
use Modules\Order\Entities\Order;
use Modules\Product\Entities\Product;
use Modules\Service\Entities\Service;
use Modules\Status\Entities\Status;

class OrderController extends Controller
{

    public function newOrderWithService(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'services' => 'required|array',
            'services.*.service_id' => 'required',
            'services.*.quantity' => 'required',
            'barber_id' => 'required',
            'barber_type' => 'required', ///salon ,person
            'reservation_day' => 'required',
            // 'reservation_time' => 'required',
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }
        $barber = User::find($request->barber_id);
        if ($barber) {
            // 'cost','discount','total',
            $order = $request->user()->orders()->create([
                'barber_id' => $request->barber_id,
                'address' => $request->address,
                'phone' => $request->phone,
                'payment_method' => $request->payment_method,
                'payment_coupon' => $request->payment_coupon,
                'reservation_time' => $request->reservation_time,
                'reservation_day' => $request->reservation_day,
                'status_id' => 1
            ]);
            $cost = 0;
            $discount = 0;
            $total = 0;
            foreach ($request->services as $i) {
                $item = Service::find($i['service_id']);
                if ($item) {
                    $readyItem = [
                        $i['service_id'] => [
                            'qty' => $i['quantity'],
                            'price' => $item->price,
                        ]
                    ];
                    $order->services()->attach($readyItem);
                }
            }

            $cost = $order->services()->sum('order_service.price');
            $payment_coupon="";
            if ($request->has('payment_coupon')) {
                $coupone = Coupon::where('code', $request->payment_coupon)->first();
                ////check if avaliable
                if ($coupone){
                    $discount = $coupone->price;
                    $payment_coupon=$request->payment_coupon;
                }
                else  return response()->json(['success' => false, 'message' => __('messages.coupone not avaliable')], 400);
            }
            $total = $cost - $discount;
            $order->update([
                'cost' => $cost,
                'discount' => $discount,
                'total' => $total,
                'payment_coupon' => $payment_coupon,

            ]);
            if ($order)
                return response()->json(['success' => true, 'data' => new OrderResource($order)], 200);
            else
                return response()->json(['success' => false, 'message' => __('messages.Try Again Later')], 400);
        } else  return response()->json(['success' => false, 'message' => __('messages.salon not exist')], 400);
    }
    public function  getCoupone(Request $request)
    {
        $validation = validator()->make($request->all(), [

            'payment_coupon' => 'required',
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['success' => false, 'errors' => $data], 402);
        }
        $coupone = Coupon::where('code', $request->payment_coupon)->first();

        ///////check if coupone avaliable or not
        if ($coupone)
            return response()->json(['success' => true, 'data' => [new CouponeResource($coupone)]], 200);
        else
            return response()->json(['success' => false, 'message' => __('messages.coupone not avaliable')], 400);
    }

    public function myOrders(Request $request)
    {
        // $orders = $request->user()->orders()->where('approve', 1)->get();

        $orders = $request->user()->orders()->get();
        if ($orders)
            return response()->json(['success' => true, 'data' => OrderResource::collection($orders)], 200);
        else
            return response()->json(['success' => false, 'message' => __('messages.Try Again Later')], 400);
    }

    public function updateOrder(Request $request)
    {

        $validation = validator()->make($request->all(), [
            'order_id' => 'required',

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }
        $order = Order::where('user_id', $request->user()->id)->where('id', $request->order_id)->first();

        if (!empty($order)) {

            if ($request->has('barber_id')) {
                $order->update([
                    'barber_id' => $request->barber_id,
                ]);
            }
            if ($request->has('address')) {
                $order->update([
                    'address' => $request->address,
                ]);
            }
            if ($request->has('phone')) {
                $order->update([
                    'phone' => $request->phone,
                ]);
            }
            if ($request->has('payment_method')) {
                $order->update([
                    'payment_method' => $request->payment_method,
                ]);
            }
            if ($request->has('payment_coupon')) {
                $order->update([
                    'payment_coupon' => $request->payment_coupon,
                ]);
            }
            if ($request->has('reservation_time')) {
                $order->update([
                    'reservation_time' => $request->reservation_time,
                ]);
            }
            if ($request->has('reservation_day')) {
                $order->update([
                    'reservation_day' => $request->reservation_day,
                ]);
            }
            if ($request->has('lat')) {
                $order->update([
                    'lat' => $request->lat,
                ]);
            }
            if ($request->has('lng')) {
                $order->update([
                    'lng' => $request->lng,
                ]);
            }
            $cost = $order->cost;
            $discount = $order->discount;
            $total = $order->total;
            if ($request->has('services')) {
                $order->services()->detach();
                foreach ($request->services as $i) {
                    $item = Service::find($i['service_id']);

                    $readyItem = [
                        $i['service_id'] => [
                            'qty' => $i['quantity'],
                            'price' => $item->price,
                        ]
                    ];

                    $order->services()->attach($readyItem);
                }
                $cost = $order->services()->sum('order_service.price');
            }


            if ($request->has('payment_coupon')) {
                $coupone = Coupon::where('code', $request->payment_coupon)->first();
                ////check if avaliable
                if ($coupone) {
                    if ($coupone->price <= $cost) {
                        $discount = $coupone->price;
                    } else return response()->json(['success' => false, 'data' => 'الخصم اكبر من الاجمالى'], 400);
                }
            }
            $total = $cost - $discount;
            $order->update([
                'cost' => $cost,
                'discount' => $discount,
                'total' => $total,
                'payment_coupon' => $request->payment_coupon,
            ]);

            return response()->json(['success' => true, 'data' => new OrderResource($order)], 200);
        } else  return response()->json(['success' => false, 'data' => 'الطلب غير موجود'], 400);
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
    public function approveOrder(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'order_id' => 'required',

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }
        $order = Order::find($request->order_id)->first();
        $order->update([
            'approve' => 1,
        ]);
        if ($order)
            return response()->json(['success' => true, 'data' => new  OrderResource($order)], 200);
        else
            return response()->json(['success' => false, 'message' => __('messages.Try Again Later')], 400);
    }
    public function cancelOrder(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'order_id' => 'required',
            'reason' => 'required',

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }
        $cancelStatus = Status::where('slug', 'cancelled')->first();
        $order = Order::find($request->order_id)->first();
        $order->update([
            'status_id' => $cancelStatus->id,
            'cancel_reason' => $request->reason
        ]);
        if ($order)
            return response()->json(['success' => true, 'data' => new  OrderResource($order)], 200);
        else
            return response()->json(['success' => false, 'message' => __('messages.Try Again Later')], 400);
    }
}
