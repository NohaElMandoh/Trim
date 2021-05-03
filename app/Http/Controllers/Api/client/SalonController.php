<?php

namespace App\Http\Controllers\Api\client;

use App\Favorite;
use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\GovernorateResource;
use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\SalonResource;
use App\Rate;
use Carbon\Carbon;
use DateTime;
use Modules\City\Entities\City;
use Modules\Governorate\Entities\Governorate;
use Modules\Order\Entities\Order;

class SalonController extends Controller
{
    public function allSalons(Request $request)
    {
        if ($request->has('name')) {
            $name = $request->name;
            $salons = User::role('salon')->where('name', 'LIKE', '%' . $name . '%')->where('is_active','1')->where('gender',auth()->user()->gender)->orderBy('created_at','desc')->paginate(10);
        } elseif ($request->has('governorate_id')) {
            $governorate_id = $request->governorate_id;
            $salons = User::role('salon')->where('governorate_id', $governorate_id)->where('is_active','1')->where('gender',auth()->user()->gender)->orderBy('created_at','desc')->paginate(10);
        }
         else   $salons = User::role('salon')->where('is_active','1')->where('gender',auth()->user()->gender)->orderBy('created_at','desc')->paginate(10);

        return response()->json(['success' => true, 'data' => SalonResource::collection($salons)], 200);
    }
    public function nearestSalons(Request $request){
        
        $validation = validator()->make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['success' => false, 'errors' => $data], 402);
        }
        $salons = User::findNearestSalons($request->lat, $request->lng, 5);
        if (count($salons) == 0) {
            $salons = User::findNearestSalons($request->lat, $request->lng, 10);
            if (count($salons) == 0) {
                $salons = User::findNearestSalons($request->lat, $request->lng, 15);
                if (count($salons) == 0) {
                    $salons = User::findNearestSalons($request->lat, $request->lng, 20);
                    if (count($salons) == 0) {
                        
                        return response()->json(['message' => __('messages.There are no salons near to you'), 'success' => false], 400);
                    }
                }
            }
        }
        return response()->json(['success' => true, 'data' => SalonResource::collection($salons)], 200);
    }
    public function salon(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'salon_id' => 'required',

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['success' => false, 'errors' => $data], 402);
        }
        $salon = User::role('salon')->where('id', $request->salon_id)->first();
        if ($salon) {
            $search = ($salon->search) + 1;
            $salon->update(['search' => $search]);
            return response()->json(['success' => true, 'data' => new SalonResource($salon)], 200);
        } else   return response()->json(['success' => false, 'message' => __('messages.salon not exist')], 400);
    }
    public function allPersons(Request $request)
    {
        if ($request->has('name')) {
            $name = $request->name;
            $salons = User::role('captain')->where('is_active','1')->where('gender',auth()->user()->gender)->where('name', 'LIKE', '%' . $name . '%')->orderBy('created_at','desc')->paginate(10);
        } elseif ($request->has('governorate_id')) {
            $governorate_id = $request->governorate_id;
            $salons = User::role('captain')->where('is_active','1')->where('gender',auth()->user()->gender)->where('governorate_id', $governorate_id)->orderBy('created_at','desc')->paginate(10);
        } else   $salons = User::role('captain')->where('is_active','1')->where('gender',auth()->user()->gender)->orderBy('created_at','desc')->paginate(10);

        return response()->json(['success' => true, 'data' => SalonResource::collection($salons)], 200);
    }
    public function rateSalon(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'salon_id' => 'required',
            'rate'  => 'required| in:1,2,3,4,5'

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }
        $rate = Rate::where('user_id', auth()->user()->id)->where('salon_id', $request->salon_id)->first();
        if ($rate) {
            $data = [
                'comment'     => $request->comment,
                'rate'      => $request->rate,
                'salon_id'      => $request->salon_id,
                'user_id' => auth()->user()->id
            ];


              auth()->user()->rates()->update($data);
              $rate = Rate::where('user_id', auth()->user()->id)->where('salon_id', $request->salon_id)->first();
        } else {
            $data = [
                'comment'     => $request->comment,
                'rate'      => $request->rate,
                'salon_id'      => $request->salon_id,
                'user_id' => auth()->user()->id
            ];


            $rate = auth()->user()->rates()->create($data);
        }

        return response()->json(['success' => true, 'data' => $rate], 200);
    }
    public function addToFavorities(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'salon_id' => 'required',

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }

        $fav = Favorite::where('user_id', auth()->user()->id)->where('salon_id', $request->salon_id)->first();
        if ($fav) {
            if ($fav->is_fav == 1) {
                $fav->update(['is_fav' => 0]);
                return response()->json(['success' => true, 'message' => __('messages.removeFromFav')], 200);
            } else {
                $fav->update(['is_fav' => 1]);
                return response()->json(['success' => true, 'message' => __('messages.addToFav')], 200);
            }
        } else {
            $data = [

                'salon_id'      => $request->salon_id,
                'user_id' => auth()->user()->id,
                'is_fav' => 1
            ];
            $fav = auth()->user()->favorities2()->create($data);
            return response()->json(['success' => true, 'message' => __('messages.addToFav')], 200);
        }
    }
    public function governorates()
    {
        $governorates = Governorate::paginate(10);
        return response()->json(['success' => true, 'data' => GovernorateResource::collection($governorates)], 200);
    }
    public function cities()
    {
        $cities = City::paginate(10);
        return response()->json(['success' => true, 'data' => CityResource::collection($cities)], 200);
    }
    public function avaliableDates(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'id' => 'required',
            'date' => 'required',

        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }
        $salon = User::find($request->id);
        if($salon){ $date = strtotime($request->date);

            $searchDay = (new Carbon($request->date))->format('l');
    
            $now = Carbon::now()->toDateTimeString();
            $to = "";
            $from = null;
            $dates = [];
            $days = $salon->works()->pluck('day');
            $daysNames = collect($salon->days());
            $daysAvaliable = $daysNames->only($days);
    
            foreach ($daysAvaliable as $i => $day) {
    
                if ($day == $searchDay) {
                    $workday = $salon->works()->where('day', $i)->first();
    
                    // if ($workday->from <= $now && $workday->to <= $now) {
                    $to = $workday->to;
                    $from = $workday->from;
                    $from_date = Carbon::createFromFormat('H:i:s', $from);
                    $to_date = Carbon::createFromFormat('H:i:s', $to);
                    $diff = $to_date->diff($from_date);
    
                    for ($i = $from_date; $i <= $to_date; $i->modify('+30 minute')) {
    
                        ////////check if timeAvaliable or not
                        if (!($this->timeAvaliable($salon->id, $i->format('g:i A'), $request->date)))
                            array_push($dates, $i->format('g:i A'));
                    }
                    // }
                }
            }
            return response()->json(['success' => true, 'data' => $dates], 200);
        }else return response()->json(['success' => false, 'message' => __('messages.salon not avaliable')], 400);
       
    }
    public function timeAvaliable($barber_id, $time, $day)
    {
        $order = Order::where([
            ['barber_id', $barber_id], ['reservation_day', $day], ['reservation_time', $time]
        ])->first();
        if ($order)
            return true;
        else
            return false;
    }
}
