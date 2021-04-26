<?php

namespace App\Http\Controllers\Api\client;

use App\Http\Controllers\Controller;
use App\Http\Resources\CouponeResource;
use App\Http\Resources\UserCouponeResource;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use Modules\Coupon\Entities\Coupon;

class CouponController extends Controller
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }
    public function allCoupones(Request $request)
    {

        $coupones = Coupon::orderBy('created_at', 'asc')->get();

        return response()->json(['success' => true, 'data' => CouponeResource::collection($coupones)], 200);
    }
    public function coupone(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'code' => 'required',

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }

        $coupone = Coupon::where('code', $request->code)->first();
        if ($coupone)
            return response()->json(['success' => true, 'data' => new CouponeResource($coupone)], 200);
        else
            return response()->json(['success' => false, 'data' => 'no coupone'], 400);
    }
    public function winCoupone(Request $request)
    {
        $validation = validator()->make($request->all(), [

            'code' => 'required',

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }
        $coupone = Coupon::where('code', $request->code)->first();

        if ($coupone) {

            $user_coupon_ids = $request->user()->coupons->pluck('pivot.coupon_id')->toArray();
            if (in_array($coupone->id, $user_coupon_ids)) {
                $usages = $request->user()->coupons()->where('coupon_id', $coupone->id)->first();
                $usage = ($usages->pivot->usage) + 1;
                $request->user()->coupons()->updateExistingPivot($coupone->id, ['usage' =>  $usage]);
              
            } else {
                $readyItem = [
                    $coupone->id => [
                        'usage' => 1,

                    ]
                ];
                $request->user()->coupons()->attach($readyItem);
            }
            $coupone =  $request->user()->coupons()->where('coupon_id', $coupone->id)->first();
            return response()->json(['success' => true, 'data' => new CouponeResource($coupone)], 200);
        } else  return response()->json(['success' => false, 'message' => __('messages.coupone not avaliable')], 400);


      
    }
    public function myCoupons(Request $request)
    {
      
        $coupons = $request->user()->coupons;

        if ($coupons) {
            return response()->json(['success' => true, 'data' =>  CouponeResource::collection($coupons)], 200);
        } else  return response()->json(['success' => false, 'message' => __('messages.coupone not avaliable')], 400);


      
    }
}
