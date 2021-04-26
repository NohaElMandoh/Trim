<?php

namespace App\Http\Controllers\Api\client;

use App\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\CouponeResource;
use App\Http\Resources\OfferOrderResource;
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
                'status_id' => 1,
                'order_type' => 'services'

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
                            'total' => $i['quantity'] * $item->price
                        ]
                    ];
                    $order->services()->attach($readyItem);
                } else return response()->json(['success' => false, 'message' => __('messages.service not exist')], 400);
            }

            $cost = $order->services()->sum('order_service.total');
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
                    } else return response()->json(['success' => false, 'message' => __('messages.coupone not avaliable')], 400);
                } else  return response()->json(['success' => false, 'message' => __('messages.coupone not in your list')], 400);
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

            if ($order)
                return response()->json(['success' => true, 'data' => new OrderResource($order)], 200);
            else
                return response()->json(['success' => false, 'message' => __('messages.Try Again Later')], 400);
        } else  return response()->json(['success' => false, 'message' => __('messages.salon not exist')], 400);
    }
    public function newOrderWithOffer(Request $request)
    {
        $validation = validator()->make($request->all(), [

            'barber_id' => 'required',
            'barber_type' => 'required', ///salon ,person
            'reservation_day' => 'required',
            'offer_id' => 'required',
            'qty' => 'required',
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
                'status_id' => 1,
                'order_type' => 'offers'
            ]);
            $cost = 0;
            $discount = 0;
            $total = 0;

            $offer = Offer::find($request->offer_id);
            if ($offer) {
                $readyItem = [
                    $request->offer_id => [
                        'qty' => $request->qty,
                        'price' => $offer->price,
                        'total' => $request->qty * $offer->price
                    ]
                ];
                $order->offers()->attach($readyItem);
            } else return response()->json(['success' => false, 'message' => __('messages.offer not exist')], 400);



            $cost = $order->offers()->sum('offer_order.total');
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
                    } else return response()->json(['success' => false, 'message' => __('messages.coupone not avaliable')], 400);
                } else return response()->json(['success' => false, 'message' => __('messages.coupone not in your list')], 400);
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

            if ($order)

                return response()->json(['success' => true, 'data' => new OrderResource($order)], 200);
            else
                return response()->json(['success' => false, 'message' => __('messages.Try Again Later')], 400);
        } else  return response()->json(['success' => false, 'message' => __('messages.salon not exist')], 400);
    }
    public function newOrderWithProduct(Request $request)
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
                } else return response()->json(['success' => false, 'message' => __('messages.coupone not avaliable')], 400);
            } else return response()->json(['success' => false, 'message' => __('messages.coupone not in your list')], 400);
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

        if ($order)
            return response()->json(['success' => true, 'data' => new OrderResource($order)], 200);
        else
            return response()->json(['success' => false, 'message' => __('messages.Try Again Later')], 400);
        // } else  return response()->json(['success' => false, 'message' => __('messages.salon not exist')], 400);
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
        $orders = $request->user()->orders()->orderBy('created_at', 'desc')->get();

        if ($orders) {

            return response()->json(['success' => true, 'data' => OrderResource::collection($orders)], 200);
        } else
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
                    } else return response()->json(['success' => false, 'message' =>  __('messages.discount greater than total')], 400);
                } else return response()->json(['success' => false, 'message' => __('messages.coupone not avaliable')], 400);
            }
            $total = $cost - $discount;
            $order->update([
                'cost' => $cost,
                'discount' => $discount,
                'total' => $total,
                'payment_coupon' => $request->payment_coupon,
            ]);

            return response()->json(['success' => true, 'data' => new OrderResource($order)], 200);
        } else    return response()->json(['success' => false, 'message' => __('messages.Order Not Exist')], 400);
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
        $order = Order::find($request->order_id);

        if ($order) {
            $order->update([
                'approve' => 1,
            ]);
            return response()->json(['success' => true, 'data' => new  OrderResource($order)], 200);
        } else
            return response()->json(['success' => false, 'message' => __('messages.Order Not Exist')], 400);
    }
    public function updateOrderPaymentMethod(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'order_id' => 'required',
            'payment_method' => 'required|in:cash,visa,fawry', //cash ,visa,fawry

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }
        $order = Order::find($request->order_id);

        if ($order) {
            $order->update([
                'payment_method' => $request->payment_method,
            ]);
            return response()->json(['success' => true, 'data' => new  OrderResource($order)], 200);
        } else
            return response()->json(['success' => false, 'message' => __('messages.Order Not Exist')], 400);
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
