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
        $lastOffers=Offer::latest()->get();
       $mostSearchedSalons=User::role('salon')->where('type','salon')->orderBy('search')->paginate(10);
       $trimStars=User::role('salon')->where('type','person')->orderBy('rate')->paginate(10);

      
        return response()->json(['success' => true, 'data' => [
             'mostSearchedSalons' =>  SalonResource::collection($mostSearchedSalons),
             'trimStars' =>  SalonResource::collection($trimStars),
             'lastOffers'=> OfferResource::collection($lastOffers)
        ]], 200);
       
    }
  
     
}
