<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
use Modules\Offer\Entities\Offer;

class MainController extends Controller
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }

    // main screen api
    public function mainLists()
    {
        $lastOffers = Offer::latest()->get();
        $mostSearchedSalons = User::role('salon')->where('type', 'salon')->orderBy('search')->paginate(10);
        //    $trimStars=User::role('salon')->where('type','person')->paginate(10);
        $trimStars = DB::table('rates')
            ->join('users', 'rates.salon_id', '=', 'users.id')
            ->select(DB::raw('avg(rate) as rate,users.id,users.name,users.image,users.cover'))
            ->groupBy('salon_id', 'users.id', 'users.name', 'users.image', 'users.cover')
            ->orderBy('rate', 'desc')
            ->get();
        $stars = collect($trimStars);

        return response()->json(['success' => true, 'data' => [
            'mostSearchedSalons' =>  SalonResource::collection($mostSearchedSalons),
            'trimStars' => $trimStars,
            'lastOffers' => OfferResource::collection($lastOffers)
        ]], 200);
    }
    public function offer(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'offer_id' => 'required',
       
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }

        $offer=offer::where('id',$request->offer_id)->first();
        if( $offer)
        return response()->json(['success' => true, 'data' => new OfferResource($offer)], 200);
        else
        return response()->json(['success' => false, 'data' => 'no offer'], 400);
    }

}
