<?php

namespace App\Http\Controllers\Api\salon;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferDiscountResource;
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
        $offers = Offer::latest()->get();
        return response()->json(['success' => true, 'data' =>  OfferDiscountResource::collection($offers)], 200);
    }
    public function services(Request $request)
    {
        $services = $request->user()->service()->latest()->get();
        return response()->json(['success' => true, 'data' =>  ServiceImageResource::collection($services)->response()->getData(true)], 200);
    }
    public function addOffer(Request $request)
    {
        validate_trans($request, [
            'name'  => 'required|string|max:255',
         
        ]);
        $validation = validator()->make($request->all(), [
            'name'  => 'required|string|max:255',
            'price' => 'required',
            'service_id' => 'required|exists:services,id',
            'category_id' => 'required',
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }
        $data   = $request->all();
        $service = Service::find($request->service_id);
        if ($service)
            $data['service_price']  = $service->price;
        else
            return response()->json(['success' => false, 'message' => __('messages.Service Not Exist')], 400);

        $service = $request->user()->offers()->create($data);
        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/offers/'; // upload path
            $photo = $request->file('image');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $service->update(['image' => 'uploads/offers/' . $name]);
        }
      
        return response()->json(['success' => true, 'data' => new OfferResource($service)], 200);
    }
}
