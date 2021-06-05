<?php

namespace App\Http\Controllers\Api\salon;

use App\CourseReservation;
use App\Http\Controllers\Controller;
use App\Http\Resources\CourseReservationsResource;
use App\Http\Resources\CourseResource;
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
use Modules\Course\Entities\Course;
use Modules\Service\Entities\Service;
use Throwable;

class CourseController extends Controller
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }


    public function courses(Request $request)
    {

        $cources = Course::get();

        return response()->json(['success' => true, 'data' =>  CourseResource::collection($cources)->response()->getData(true)], 200);
    }
    public function reserve_course(Request $request)
    {
        $validation = validator()->make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'governorate_id' => 'required',
            'phone' => 'required',
            'payment_type' => 'required',
            'course_id' => 'required',
        ]);

      
        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }
        $data   = $request->all();

        $course = Course::find($request->course_id);
       
        if ($course) {
            $price=$course->price;
            $data['price'] = $price;
        }

        $reservation = $request->user()->courseReservation()->create($data);

        // return    $reservation ;
        return response()->json(['success' => true, 'data' => new CourseReservationsResource($reservation)], 200);
    }
}
