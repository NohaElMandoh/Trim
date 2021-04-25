<?php

namespace App\Http\Controllers\Api;

use App\Favorite;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\GovernorateResource;
use App\Http\Resources\OfferResource;
use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\SalonResource;
use App\Rate;
use Carbon\Carbon;
use DateTime;
use Modules\City\Entities\City;
use Modules\Governorate\Entities\Governorate;
use Modules\Offer\Entities\Offer;
use Modules\Order\Entities\Order;

class OfferController extends Controller
{
    public function offer(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'offer_id' => 'required',

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['success' => false, 'errors' => $data], 402);
        }
        $offer = Offer::find( $request->offer_id);
        if ($offer) {
            return response()->json(['success' => true, 'data' => new OfferResource($offer)], 200);
        } else   return response()->json(['success' => false, 'message' => __('messages.offer not exist')], 400);
    }
   
}
