<?php

namespace App\Http\Controllers\Api\salon;

use App\Http\Controllers\Controller;
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
use Modules\Service\Entities\Service;
use Throwable;

class ServiceController extends Controller
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }


    public function addService(Request $request)
    {

        validate_trans($request, [
            'title'  => 'required|string|max:255',
            'description'  => 'nullable|string|max:255',
        ]);

        $validation = validator()->make($request->all(), [
            // 'en' => 'required|array',
            // 'en.title' => 'required',
            // 'en.description' => 'required',
            // 'ar' => 'required|array',
            // 'ar.title' => 'required',
            // 'ar.description' => 'required',
            'title'  => 'required|string|max:255',
            'description'  => 'nullable|string|max:255',
            'price_type'    => 'required|in:normal,range',
            'gender'        => 'required|in:male,female',
            'price'         => 'nullable|required_if:price_type,normal|numeric',
            'min_price'     => 'nullable|required_if:price_type,range|numeric',
            'max_price'     => 'nullable|required_if:price_type,range|numeric',
            'image' => 'required',
        ]);

        if ($validation->fails()) {
            $data = $validation->errors();
            return response()->json(['errors' => $data, 'success' => false], 402);
        }
        $data   = $request->all();
        // $data['for_children'] = (boolean) $request->for_children;
        $service = $request->user()->service()->create($data);
        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/services/'; // upload path
            $photo = $request->file('image');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $service->update(['image' => 'uploads/services/' . $name]);
        }
        return response()->json(['success' => true, 'data' => new ServiceResource($service)], 200);
    }
    public function services(Request $request)
    {

        $services = $request->user()->service()->paginate(10);

        return response()->json(['success' => true, 'data' =>  ServiceResource::collection($services)->response()->getData(true)], 200);
    }
}
