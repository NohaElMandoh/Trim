<?php

namespace Modules\Order\Http\Controllers;

use App\Setting;
use App\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Notification;
use Modules\Order\Entities\Order;
use Illuminate\Support\Facades\Validator;
use Modules\Order\Notifications\NewOrderNotification;
use Modules\Status\Entities\Status;
use Modules\Coupon\Entities\Coupon;

class ApiOqController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shop_id'       => 'required|exists:users,id',
            'branch_id'     => 'required|exists:branches,id',
            'delivery_lat'  => 'required|numeric',
            'delivery_lng'  => 'required|numeric',
            'payment_method' => 'required|in:cash,fawry,points',
            'delivery_fee'  => 'required|numeric',
            'products'      => 'nullable|required_without:offers|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.qty' => 'required|integer',
            'offers'        => 'nullable|required_without:products|array',
            'offers.*.id'   => 'required|exists:offers,id',
            'offers.*.qty'  => 'required|integer',
            'payment_coupon'    => 'nullable|exists:coupons,code'
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
        }
        $data               = $request->all();
        $data['status_id']  = Status::where('slug', 'search-captain')->firstOrFail()->id;
        $data['user_id']    = auth()->id();
        $data['type']       = 'oq';
        $user               = auth()->user();
        if ($request->payment_method == "points") {
            $setting    = Setting::firstOrFail();
            if (($user->points * $setting->point_price) <= $data['delivery_fee'] && ($user->points * $setting->point_price) >= 0) {
                $data['delivery_fee']   -= ($user->points * $setting->point_price);
                $user->points           = 0;
            } else {
                $points                 = $user->points - ($data['delivery_fee'] / $setting->point_price);
                $data['delivery_fee']   = 0;
                $user->points           = $points;
            }
        }
        $coupon = null;
        if ($request->has('payment_coupon') && $request->payment_coupon) {
            $coupon             = Coupon::where('code', $request->payment_coupon)->where('oq', 1)->whereHas('users', function ($query) {
                $query->where('id', auth()->id());
            })->with('users')->first();
            if ($coupon) {
                $date1 = new DateTime($coupon->created_at);
                $date2 = new DateTime(date('Y-m-d H:i:s'));

                $diff = $date2->diff($date1);

                $hours = $diff->h;
                $hours = $hours + ($diff->days * 24);
                if ($coupon->usage_number_times - $coupon->users[0]->pivot->usage <= 0) {
                    return response()->json(['message' => __('messages.Reached max of usage'), 'success' => false], 401);
                } elseif ($hours > $coupon->duration) {
                    return response()->json(['message' => __('messages.Invalid coupon'), 'success' => false], 401);
                }
                $coupon->users()->updateExistingPivot(auth()->user(), ['usage' => $coupon->users[0]->pivot->usage + 1], false);
            } else {
                return response()->json(['message' => __('messages.Invalid coupon'), 'success' => false], 401);
            }
            if ($coupon->price <= $data['delivery_fee']) {
                $data['delivery_fee']   -= $coupon->price;
            } else {
                $data['delivery_fee'] = 0;
            }
        }
        $user->save();
        $order              = Order::create($data);
        if ($request->products) {
            foreach ($request->products as $product) {
                $order->products()->attach($product['id'], ['qty' => $product['qty']]);
            }
        }
        if ($request->offers) {
            foreach ($request->offers as $offer) {
                $order->offers()->attach($offer['id'], ['qty' => $offer['qty']]);
            }
        }
        $captains = User::findNearestCaptains($order->delivery_lat, $order->delivery_lng, 5);
        if (count($captains) == 0) {
            $captains = User::findNearestCaptains($order->delivery_lat, $order->delivery_lng, 10);
            if (count($captains) == 0) {
                $captains = User::findNearestCaptains($order->delivery_lat, $order->delivery_lng, 15);
                if (count($captains) == 0) {
                    $captains = User::findNearestCaptains($order->delivery_lat, $order->delivery_lng, 20);
                    if (count($captains) == 0) {
                        $order->delete();
                        if ($coupon) {
                            $coupon->users()->updateExistingPivot(auth()->user(), ['usage' => $coupon->users[0]->pivot->usage - 1], false);
                        }
                        return response()->json(['message' => __('messages.There are no captains near to you'), 'success' => false], 200);
                    }
                }
            }
        }
        Notification::send($captains, new NewOrderNotification($order));
        return response()->json(['message' => __('messages.Order created'), 'success' => true], 200);
    }
}
