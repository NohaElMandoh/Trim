<?php

namespace Modules\Coupon\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Notification;
use Modules\Coupon\Entities\Coupon;
use Modules\Coupon\Notifications\NewCouponNotification;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:coupon.list')->only('index');
        $this->middleware('permission:coupon.create')->only(['create', 'store']);
        $this->middleware('permission:coupon.view')->only('show');
        $this->middleware('permission:coupon.edit')->only(['edit', 'update']);
        $this->middleware('permission:coupon.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows = Coupon::latest()->get();
        return view('coupon::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('coupon::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        validate_trans($request, [
            'title' => 'required|string|max:255'
        ]);
        $request->validate([
            'name'          => 'nullable|string|max:255',
            'code'          => 'required|unique:coupons,code|max:255',
            'duration'      => 'required|integer',
            'price'         => 'required|numeric',
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'usage_number_times' => 'required|integer',
            'roles.*'       => 'exists:roles,id',
            'governorate_id'=> 'nullable|exists:governorates,id',
            'city_id'       => 'nullable|exists:cities,id',
        ]);

        $data               = $request->all();
        $data['anywhere']   = (bool) $request->anywhere;
        $data['moreway']    = (bool) $request->moreway;
        $data['oneway']     = (bool) $request->oneway;
        $data['oq']         = (bool) $request->oq;
        $data['week']       = (bool) $request->week;
        $data['image']      = upload_image($request, 'image', 800, 400);
        $row                = Coupon::create($data);
        $row->roles()->attach($request->roles);
        $users = [];
        if ($request->roles) {
            $users = User::whereHas('roles', function($query) use($request) {
                $query->whereIn('id', $request->roles);
            });
        } else {
            $users = User::doesntHave('roles');
        }
        if ($request->name) {
            $users->where('name', 'LIKE', '%' . $request->name . '%');
        }
        if($request->governorate_id) {
            $users->where('governorate_id', $request->governorate_id);
        }
        if($request->city_id) {
            $users->where('city_id', $request->city_id);
        }
        $users = $users->get();
        $row->users()->attach($users);
        Notification::send($users, new NewCouponNotification($row));
        return redirect()->route('coupons.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row               = Coupon::findOrFail($id);
        return view('coupon::view', compact('row'));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row   = Coupon::findOrFail($id);
        $row->delete();
        return redirect()->route('coupons.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
