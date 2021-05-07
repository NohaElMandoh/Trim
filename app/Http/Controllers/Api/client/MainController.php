<?php

namespace App\Http\Controllers\Api\client;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Http\Resources\SalonResource;
use App\Http\Resources\StarsResource;
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
use App\Rate;
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
    public function mainLists(Request $request)
    {
        $lastOffers = Offer::latest()->get();
        $mostSearchedSalons = User::role('salon')->orderBy('search')->paginate(10);
        $trimStars = User::role('captain')
            ->distinct('users.id')
            ->select(DB::raw('avg(rate) as rate,users.id,users.name,users.image,users.cover'))
            ->join('rates', 'rates.salon_id', '=', 'users.id')
            ->groupBy('salon_id', 'users.id', 'users.name', 'users.image', 'users.cover')
            ->orderBy('rate', 'desc')
            ->get();

        if ($trimStars->count() == 0) {

            $trimStars = User::role('captain')->orderBy('search', 'desc')->paginate(10);
        }

        return response()->json(['success' => true, 'data' => [
            'mostSearchedSalons' =>  SalonResource::collection($mostSearchedSalons),
            'trimStars' => StarsResource::collection($trimStars),
            'lastOffers' => OfferResource::collection($lastOffers)
        ]], 200);
    }
    public function myFav(Request $request)
    {

        // $salonsIds = $request->user()->favorities()->where('pivot.is_fav',true)->pluck('salon_id')->toArray();
        $salonsIds = $request->user()->favorities()->where(function ($q) {
            $q->where('is_fav', 1);
             
        })->pluck('salon_id')->toArray();
       
        $salons = User::whereIn('id', $salonsIds)->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true, 'data' =>  SalonResource::collection($salons)
        ], 200);
    }
}
