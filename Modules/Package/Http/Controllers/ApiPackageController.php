<?php

namespace Modules\Package\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Package\Entities\Package;
use Modules\Package\Entities\PackageTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Illuminate\Support\Facades\Validator;

class ApiPackageController extends Controller
{
    public $fractal;
    public function __construct()
    {
        $this->fractal = new Manager();
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $packages = Package::orderBy('order')->get();
        $resource = new Collection($packages, new PackageTransformer);
        return response_api($this->fractal->createData($resource)->toArray());
    }

    public function send_points(Request $request)
    {
        $validation = [
            'email'     => 'required|exists:users,email',
            'points'    => 'required|numeric|min:1',
        ];
        $validator = Validator::make($request->all(), $validation);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors(), 'success' => false], 401);
        }
        if (auth()->user()->points < $request->points) {
            return response_api([], false, __('messages.You do not have points to send'));
        }
        $user = User::where('email', $request->email)->first();
        $user->points += $request->points;
        $user->save();

        $sentUser = auth()->user();
        $sentUser->points -= $request->points;
        $sentUser->save();
        return response_api([], true, __('messages.Points sent successfully'));
    }
}
