<?php

namespace Modules\Coupon\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Coupon\Entities\CouponTransformer;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Modules\Coupon\Entities\Coupon;

class ApiCouponController extends Controller
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
        $paginator  = Coupon::whereHas('users', function ($query) {
            $query->where('id', auth()->id());
        })->latest()->paginate(10);
        $coupons = $paginator->getCollection();
        $resource = new Collection($coupons, new CouponTransformer);
        $resource->setPaginator(new IlluminatePaginatorAdapter($paginator));
        return response_api($this->fractal->createData($resource)->toArray());
    }

    public function validate_coupon(Request $request)
    {
        $coupon = Coupon::where('code', $request->code)->where($request->column, 1)->whereHas('users', function ($query) {
            $query->where('id', auth()->id());
        })->with('users')->first();
        if ($coupon) {
            $date1 = new DateTime($coupon->created_at);
            $date2 = new DateTime(date('Y-m-d H:i:s'));

            $diff = $date2->diff($date1);

            $hours = $diff->h;
            $hours = $hours + ($diff->days * 24);
            if ($coupon->usage_number_times - $coupon->users[0]->pivot->usage <= 0) {
                return response()->json(['message' => __('messages.Reached max of usage'), 'success' => false], 401);
            } elseif( $hours > $coupon->duration) {
                return response()->json(['message' => __('messages.Invalid coupon'), 'success' => false], 401);
            } else {
                $resource = new Item($coupon, new CouponTransformer);
                return response_api($this->fractal->createData($resource)->toArray());
            }
        } else {
            return response()->json(['message' => __('messages.Invalid coupon'), 'success' => false], 401);
        }
    }
}
