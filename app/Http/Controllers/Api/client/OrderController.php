<?php

namespace App\Http\Controllers\Api\client;

use App\Cart;
use App\Events\clientnotify;
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
use Carbon\Carbon;
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
                            'qty' => 1,
                            'price' => $item->price,
                            'total' =>  $item->price
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
                    $currentDateTime = Carbon::now();
                    // $end_at = Carbon::now()->addHours(5);
                    $end_at = Carbon::parse($coupone['created_at'])->addHours($coupone->duration); //get end time of coupone 
                    $user_coupon_ids = $request->user()->coupons->pluck('pivot.coupon_id')->toArray();
                 
                    if (!in_array($coupone->id, $user_coupon_ids)) { //check if user use this coupone before
                       
                        if ($coupone->usage_number_times > 0 && ($end_at >=   $currentDateTime)) { //check if coupone usage time >0 and check if duration end or not 
                            $discount = $coupone->price;
                            $usage = ($coupone->usage_number_times) - 1;
                            $coupone->update(['usage_number_times' => $usage]);
    
                            $readyItem = [
                                $coupone->id => [
                                    'usage' => 1,
            
                                ]
                            ];
                            $request->user()->coupons()->attach($readyItem);
                            // $user_coupon_ids = $request->user()->coupons->pluck('pivot.coupon_id')->toArray();
                            // if (in_array($coupone->id, $user_coupon_ids)) {
                            //     $usages = $request->user()->coupons()->where('coupon_id', $coupone->id)->first();
                            //     if ($usages->pivot->usage > 0) {
                            //         $usage = ($usages->pivot->usage) - 1;
                            //         $request->user()->coupons()->updateExistingPivot($coupone->id, ['usage' =>  $usage]);
                            //         $discount = $coupone->price;
                            //         $payment_coupon = $request->payment_coupon;
                            //     } else return response()->json(['success' => false, 'message' => __('messages.cant use coupone')], 400);
                            // } else return response()->json(['success' => false, 'message' => __('messages.coupone not in your list')], 400);
                        } else {
                            $order->services()->detach($order->id);
                            $order->delete();
                            return response()->json(['success' => false, 'message' => __('messages.coupone not avaliable')], 400);
                        }
                    } else {
                        $order->services()->detach($order->id);
                        $order->delete();
                        return response()->json(['success' => false, 'message' => __('messages.you already used this coupone before')], 400);
                    }
                } else {
                    $order->services()->detach($order->id);
                    $order->delete();
                    return response()->json(['success' => false, 'message' => __('messages.coupone not avaliable')], 400);
                }
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
            $mg1=$request->user()->name.'create new order with service';
            
            $barber=User::find($request->barber_id);
           
            // $this->sendOrderNotification($mg1, $order,$barber);
            event(new clientnotify($order, $mg1,$barber));
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
                        'qty' => 1,
                        'price' => $offer->price,
                        'total' =>  $offer->price
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
                    $currentDateTime = Carbon::now();
                    // $end_at = Carbon::now()->addHours(5);
                    $end_at = Carbon::parse($coupone['created_at'])->addHours($coupone->duration); //get end time of coupone 
                    $user_coupon_ids = $request->user()->coupons->pluck('pivot.coupon_id')->toArray();
                 
                    if (!in_array($coupone->id, $user_coupon_ids)) { //check if user use this coupone before
                       
                        if ($coupone->usage_number_times > 0 && ($end_at >=   $currentDateTime)) { //check if coupone usage time >0 and check if duration end or not 
                            $discount = $coupone->price;
                            $usage = ($coupone->usage_number_times) - 1;
                            $coupone->update(['usage_number_times' => $usage]);
    
                            $readyItem = [
                                $coupone->id => [
                                    'usage' => 1,
            
                                ]
                            ];
                            $request->user()->coupons()->attach($readyItem);
                            // $user_coupon_ids = $request->user()->coupons->pluck('pivot.coupon_id')->toArray();
                            // if (in_array($coupone->id, $user_coupon_ids)) {
                            //     $usages = $request->user()->coupons()->where('coupon_id', $coupone->id)->first();
                            //     if ($usages->pivot->usage > 0) {
                            //         $usage = ($usages->pivot->usage) - 1;
                            //         $request->user()->coupons()->updateExistingPivot($coupone->id, ['usage' =>  $usage]);
                            //         $discount = $coupone->price;
                            //         $payment_coupon = $request->payment_coupon;
                            //     } else return response()->json(['success' => false, 'message' => __('messages.cant use coupone')], 400);
                            // } else return response()->json(['success' => false, 'message' => __('messages.coupone not in your list')], 400);
                        } else {
                            $order->services()->detach($order->id);
                            $order->delete();
                            return response()->json(['success' => false, 'message' => __('messages.coupone not avaliable')], 400);
                        }
                    } else {
                        $order->services()->detach($order->id);
                        $order->delete();
                        return response()->json(['success' => false, 'message' => __('messages.you already used this coupone before')], 400);
                    }
                } else {
                    $order->services()->detach($order->id);
                    $order->delete();
                    return response()->json(['success' => false, 'message' => __('messages.coupone not avaliable')], 400);
                }
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
            $mg1=$request->user()->name.'create new order with offer';
            
            $barber=User::find($request->barber_id);
           
            // $this->sendOrderNotification($mg1, $order,$barber);
            event(new clientnotify($order, $mg1,$barber));
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
                $currentDateTime = Carbon::now();
                // $end_at = Carbon::now()->addHours(5);
                $end_at = Carbon::parse($coupone['created_at'])->addHours($coupone->duration); //get end time of coupone 
                $user_coupon_ids = $request->user()->coupons->pluck('pivot.coupon_id')->toArray();
             
                if (!in_array($coupone->id, $user_coupon_ids)) { //check if user use this coupone before
                   
                    if ($coupone->usage_number_times > 0 && ($end_at >=   $currentDateTime)) { //check if coupone usage time >0 and check if duration end or not 
                        $discount = $coupone->price;
                        $usage = ($coupone->usage_number_times) - 1;
                        $coupone->update(['usage_number_times' => $usage]);

                        $readyItem = [
                            $coupone->id => [
                                'usage' => 1,
        
                            ]
                        ];
                        $request->user()->coupons()->attach($readyItem);
                        // $user_coupon_ids = $request->user()->coupons->pluck('pivot.coupon_id')->toArray();
                        // if (in_array($coupone->id, $user_coupon_ids)) {
                        //     $usages = $request->user()->coupons()->where('coupon_id', $coupone->id)->first();
                        //     if ($usages->pivot->usage > 0) {
                        //         $usage = ($usages->pivot->usage) - 1;
                        //         $request->user()->coupons()->updateExistingPivot($coupone->id, ['usage' =>  $usage]);
                        //         $discount = $coupone->price;
                        //         $payment_coupon = $request->payment_coupon;
                        //     } else return response()->json(['success' => false, 'message' => __('messages.cant use coupone')], 400);
                        // } else return response()->json(['success' => false, 'message' => __('messages.coupone not in your list')], 400);
                    } else {
                        $order->services()->detach($order->id);
                        $order->delete();
                        return response()->json(['success' => false, 'message' => __('messages.coupone not avaliable')], 400);
                    }
                } else {
                    $order->services()->detach($order->id);
                    $order->delete();
                    return response()->json(['success' => false, 'message' => __('messages.you already used this coupone before')], 400);
                }
            } else {
                $order->services()->detach($order->id);
                $order->delete();
                return response()->json(['success' => false, 'message' => __('messages.coupone not avaliable')], 400);
            }
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
    public function checkout(Request $request){

        $validation = validator()->make($request->all(), [
            'payment_id' => 'required',
            'status' => 'required|in:success,failed',
        
           
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }

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

        if ($coupone) {
            if ($coupone->usage_number_times > 0) {
                return response()->json(['success' => true, 'data' => [new CouponeResource($coupone)]], 200);
            } else {
                return response()->json(['success' => false, 'message' => __('messages.coupone not avaliable')], 400);
            }
        } else {
            return response()->json(['success' => false, 'message' => __('messages.coupone not avaliable')], 400);
        }
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
        $order = Order::find($request->order_id);
        if ($order) {
            $order->update([
                'status_id' => $cancelStatus->id,
                'cancel_reason' => $request->reason
            ]);
        } else return response()->json(['success' => false, 'message' => __('messages.Order Not Exist')], 400);
        event(new clientnotify($order, 'your order cancelled'));
        if ($order)
            return response()->json(['success' => true, 'data' => new  OrderResource($order)], 200);
        else
            return response()->json(['success' => false, 'message' => __('messages.Try Again Later')], 400);
    }
    public function confirmOrder(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }
        $deliveredStatus = Status::where('slug', 'delivered')->first();
        $order = Order::find($request->order_id);
        if ($order) {
            $order->update([
                'status_id' => $deliveredStatus->id
            ]);
        } else return response()->json(['success' => false, 'message' => __('messages.Order Not Exist')], 400);

        // event(new clientnotify($order, 'Thank You'));
        if ($order)
            return response()->json(['success' => true, 'data' => new  OrderResource($order)], 200);
        else
            return response()->json(['success' => false, 'message' => __('messages.Try Again Later')], 400);
    }
    public function rateOrder(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'order_id' => 'required',
            'rate'  => 'required| in:1,2,3,4,5'

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }
        $order = Order::find($request->order_id);
        if ($order) {
            $order->update(['rate' => $request->rate, 'review' => $request->review]);
        }

        return response()->json(['success' => true, 'data' => 'Done'], 200);
    }
    public function sendOrderNotification($mg1, $order, $salon_id)
    {
        //notifiable_type,notifiable_id,data,read_at
        // $text = Auth()->user()->name . " {$mg1}";

        // $admin = User::where('email', 'admin@admin.com')->get();
        // $collection1 = collect($admin);
        // $client_notify = Client::where('id', $client->id)->get();
        // $ids[] = $merchants_ids;
        // $merchants = Merchant::whereIn('id', $ids)->get();


        // $merged = $collection1->merge($client_notify)->merge($merchants);
        // $merged_all = $merged->all();
        event(new clientnotify($order, $mg1,$salon_id));

        // $tokens = $client->tokens()->where('token', '!=', '')->pluck('token')->toArray();
        // $audience = ['include_player_ids' => $tokens];
        // $contents = [
        //     'en' => 'You have created New order  ',
        //     'ar' => 'لقد قمت بعمل طلب جديد',
        // ];
        // // $send = notifyByOneSignal($audience , $contents , [
        // //     'user_type' => 'merchant',
        // //     'action' => 'new-order',
        // //     'order_id' => $order->id,
        // // ]);
        // // $send = json_decode($send);
        // // return $tokens;
        // if (count($tokens)) {

        //     $title = $mg1;
        //     $body = $mg1;
        //     $data = [
        //         'action' => 'Notification',
        //         'order' => 'Notification'
        //     ];
        //     $send = notifyByFirebase($title, $body, $tokens, $data);
        //     info("firebase result: " . $send);
        // }
    }
    // public function send_notification(Request $request)
    // {
    //     $request->validate([
    //         'name'          => 'nullable|string|max:255',
    //         'type'          => 'required|in:user_app,captain_app',
    //         'title'         => 'required|string|max:255',
    //         'description'   => 'nullable|string|max:255',
    //     ]);

    //     if ($request->name) {
    //         $tokens = Token::where('type', $request->type)->whereHas('user', function ($query) use($request) {
    //             $query->where('name', 'LIKE', '%' . $request->name . '%');
    //         })->get()->pluck('token');
    //         send_notif($request->title, ['url' => '', 'event' => 'notification', 'notif_id' => ''], $request->description, $tokens);
    //     } else {
    //         $tokens = Token::where('type', $request->type)->get()->pluck('token');
    //         send_notif($request->title, ['url' => '', 'event' => 'notification', 'notif_id' => ''], $request->description, $tokens);
    //     }
    //     return redirect()->route('dashboard')->with(['status' => 'success', 'message' => __('Notification sent')]);
    // }
}
