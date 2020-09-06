<?php

namespace Modules\Salon\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;
use Modules\Salon\Entities\WorkDay;

class SalonController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:salon.list')->only('index');
        $this->middleware('permission:salon.create')->only(['create', 'store']);
        $this->middleware('permission:salon.view')->only('show');
        $this->middleware('permission:salon.edit')->only(['edit', 'update']);
        $this->middleware('permission:salon.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows = User::role('salon')->latest()->get();
        return view('salon::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('salon::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string|max:255',
            'email'         => 'required|string|email|unique:users,email|max:255',
            'phone'         => 'required|string|unique:users,phone|max:255',
            'password'      => 'required|string|min:6|max:255',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cover'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'commercial_register'      => 'required|file|max:2048',
            'gender'        => 'required|in:male,female',
            'governorate_id'=> 'required|exists:governorates,id',
            'city_id'       => 'required|exists:cities,id',
            'works'         => 'required|array',
            'works.*.from'  => 'required|string|max:255',
            'works.*.to'    => 'required|string|max:255',
            'services'      => 'nullable|array',
            'services.*'    => 'required|exists:services,id'
        ]);

        $data               = $request->all();
        $data['password']   = bcrypt($request->password);
        $data['is_active']  = (bool) $request->is_active;
        $data['is_sponsored']  = (boolean) $request->is_sponsored; 
        $data['image']      = $request->hasFile('image') ? upload_image($request, 'image', 200, 200) : 'salon.png';
        $data['cover']      = $request->hasFile('cover') ? upload_image($request, 'cover', 800, 400) : 'salon.png';
        $data['commercial_register']   = upload_file($request, 'commercial_register');
        $row                = User::create($data);
        $salon              = config('permission.models.role')::where('name', 'salon')->firstOrFail();
        $row->roles()->attach($salon);
        $row->services()->sync($request->services);
        if($request->works) {
            foreach($request->works as $work) {
                $row->works()->create($work);
            }
        }
        return redirect()->route('salons.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row               = User::findOrFail($id);
        return view('salon::view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $row               = User::findOrFail($id);
        $selected          = $row->services()->pluck('id')->toArray();
        return view('salon::edit', compact('row', 'selected'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string|max:255',
            'email'         => 'required', 'string', 'email', Rule::unique('users', 'email')->ignore($id), 'max:255',
            'phone'         => 'required', 'string', Rule::unique('users', 'phone')->ignore($id), 'max:255',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'cover'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gender'        => 'required|in:male,female',
            'commercial_register'      => 'nullable|file|max:2048',
            'governorate_id'=> 'required|exists:governorates,id',
            'city_id'       => 'required|exists:cities,id',
            'works'         => 'required|array',
            'works.*.from'  => 'required|string|max:255',
            'works.*.to'    => 'required|string|max:255',
            'services'      => 'nullable|array',
            'services.*'    => 'required|exists:services,id'
        ]);

        $data               = $request->all();
        $data['is_active']  = (bool) $request->is_active;
        $data['is_sponsored']  = (boolean) $request->is_sponsored; 
        if ($request->hasFile('image'))
            $data['image']      = upload_image($request, 'image', 200, 200);

        if ($request->hasFile('cover'))
            $data['cover']      = upload_image($request, 'cover', 800, 400);

        if ($request->hasFile('commercial_register'))
            $data['commercial_register']   = upload_file($request, 'commercial_register');

        $row                = User::findOrFail($id);
        $row->update($data);
        $row->services()->sync($request->services);
        WorkDay::where('user_id', $row->id)->delete();
        if($request->works) {
            foreach($request->works as $work) {
                $row->works()->create($work);
            }
        }
        return redirect()->route('salons.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    public function edit_password($id)
    {
        $row               = User::findOrFail($id);
        return view('salon::password', compact('row'));
    }

    public function update_password(Request $request, $id)
    {
        $request->validate([
            'password'    => 'required|string|min:6|max:255',
        ]);
        $data               = $request->all();
        $data['password']   = bcrypt($request->password);
        $row                = User::findOrFail($id);
        $row->update($data);
        return redirect()->route('salons.index')->with(['status' => 'success', 'message' => __('Password update successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row   = User::findOrFail($id);
        $row->delete();
        return redirect()->route('salons.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
