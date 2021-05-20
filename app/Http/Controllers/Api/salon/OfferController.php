<?php

namespace App\Http\Controllers\Api\salon;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Http\Resources\ServiceResource;
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
use Astrotomic\Translatable\Validation\RuleFactory;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Modules\Offer\Entities\Offer;
use Modules\Order\Entities\Order;
use Modules\Service\Entities\Service;
use Throwable;

class OfferController extends Controller
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }


    public function offers(Request $request)
    {
        $offers = Offer::latest()->paginate(10);
       return response()->json(['success' => true, 'data' =>  OfferResource::collection($offers)->response()->getData(true)], 200);

       
    }
}
