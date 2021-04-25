<?php

namespace App\Http\Controllers\Api\client;

use App\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\CouponeResource;
use App\Http\Resources\OfferResource;
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
use Modules\Product\Entities\Product;
use Modules\Service\Entities\Service;

class CouponController extends Controller
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }
    public function allCoupones(Request $request)
    {
     
        $coupones=Coupon::orderBy('created_at','asc')->get();

        return response()->json(['success' => true, 'data' => CouponeResource::collection( $coupones)], 200);
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

        $coupone=Coupon::where('code',$request->code)->first();
        if( $coupone)
        return response()->json(['success' => true, 'data' => new CouponeResource($coupone)], 200);
        else
        return response()->json(['success' => false, 'data' => 'no coupone'], 400);
    }

}
