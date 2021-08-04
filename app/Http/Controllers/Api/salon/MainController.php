<?php

namespace App\Http\Controllers\Api\salon;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\GovernorateResource;
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
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Modules\City\Entities\City;
use Modules\Governorate\Entities\Governorate;
use Modules\Offer\Entities\Offer;

class MainController extends Controller
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }

    // main screen api
    public function statistics(Request $request)
    {
        $allOrders = $request->user()->shop_orders()->get(); //all
        $completedOrders = $request->user()->shop_orders()->where('status_id', 5)->get(); //delivered
        $waitingOrders = $request->user()->shop_orders()->where('status_id', 1)->get(); //wating captin
        $now = Carbon::now();
        // $weekStartDate = $now->startOfWeek()->subDays(2)->format('Y-m-d H:i');//using subDays(2) to start week from satarday 
        // $weekEndDate = $now->endOfWeek()->addDays(5)->format('Y-m-d H:i');//using addDays(5) to end week on friday
        $from = $now->startOfWeek()->subDays(2);
        $to = $from->addDays(6);
        $weeklyOrders = $request->user()->shop_orders()->whereBetween('reservation_day',[date($from),date($to)])->get(); 

        return response()->json(['success' => true, 'data' => [
            'allOrders' => $allOrders->count(),
            'completedOrders' => $completedOrders->count(),
            'waitingOrders' => $waitingOrders->count(),
            'weeklyOrders' => $weeklyOrders,
            'from'=>$from,
            'to'=>$now->endOfWeek()->addDays(5)->format('Y-m-d H:i'),
            'orders'=>$request->user()->shop_orders()->get()
            
           
        ]], 200);
    }
    public function lastOffers(Request $request)
    {
        $lastOffers = Offer::latest()->get();

        return response()->json(['success' => true, 'data' =>
        OfferResource::collection($lastOffers), 200]);
    }
}
