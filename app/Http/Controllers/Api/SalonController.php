<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CityResource;
use App\Http\Resources\GovernorateResource;
use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\SalonResource;
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
            $salons = User::role('salon')->where('type', 'salon')->where('name', 'LIKE', '%' . $name . '%')->paginate(10);
        } elseif ($request->has('governorate_id')) {
            $governorate_id = $request->governorate_id;
            $salons = User::role('salon')->where('type', 'salon')->where('governorate_id', $governorate_id)->paginate(10);
        } else   $salons = User::role('salon')->where('type', 'salon')->paginate(10);

        return response()->json(['data' => SalonResource::collection($salons), 'success' => true], 200);
    }
    public function salon(Request $request)
    {
        $salon = User::where('id', $request->id)->first();
        $search = ($salon->search) + 1;
        $salon->update(['search' => $search]);
        return response()->json(['data' => new SalonResource($salon), 'success' => true], 200);
    }
    public function allPersons(Request $request)
    {
        if ($request->has('name')) {
            $name = $request->name;
            $salons = User::role('salon')->where('type', 'person')->where('name', 'LIKE', '%' . $name . '%')->paginate(10);
        } elseif ($request->has('governorate_id')) {
            $governorate_id = $request->governorate_id;
            $salons = User::role('salon')->where('type', 'person')->where('governorate_id', $governorate_id)->paginate(10);
        } else   $salons = User::role('salon')->where('type', 'person')->paginate(10);

        return response()->json(['data' => SalonResource::collection($salons), 'success' => true], 200);
    }
    public function rateSalon(Request $request)
    {
        $data = [
            'comment'     => $request->comment,
            'rate'      => $request->rate,
            'salon_id'      => $request->salon_id,
            'user_id' => auth()->user()->id
        ];
        $rate = auth()->user()->rates()->create($data);
        return response()->json(['success' => true, 'data' => $rate], 200);
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
        $salon = User::find($request->id);
        $date = strtotime($request->date);

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
                    if( ! ($this-> timeAvaliable($salon->id,$i->format('g:i A'),$request->date)))
                    array_push($dates, $i->format('g:i A'));
                }
                // }
            }
        }
        return response()->json(['success' => true, 'data' => $dates], 200);
    }
    public function timeAvaliable($barber_id, $time, $day)
    {
        $order = Order::where([['barber_id', $barber_id],['reservation_day', $day],['reservation_time',$time]
        ])->first();
        if($order)
        return true;
        else
        return false;
    }
}
