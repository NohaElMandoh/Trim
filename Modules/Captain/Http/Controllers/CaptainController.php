<?php

namespace Modules\Captain\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rule;

class CaptainController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:captain.list')->only('index');
        $this->middleware('permission:captain.create')->only(['create', 'store']);
        $this->middleware('permission:captain.view')->only('show');
        $this->middleware('permission:captain.edit')->only(['edit', 'update']);
        $this->middleware('permission:captain.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows = User::role('captain')->latest()->get();
        return view('captain::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('captain::create');
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
            'email'         => 'required|string|email|unique:users,email|max:255',
            'phone'         => 'required|string|unique:users,phone|max:255',
            'password'      => 'required|string|min:6|max:255',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'id_photo'      => 'required|file|max:2048',
            'gender'        => 'required|in:male,female',
            'governorate_id'=> 'required|exists:governorates,id',
            'city_id'       => 'required|exists:cities,id',
            'services'      => 'nullable|array',
            'services.*'    => 'required|exists:services,id'
        ]);

        $data               = $request->all();
        $data['is_sponsored']  = (boolean) $request->is_sponsored; 
        $data['password']   = bcrypt($request->password);
        $data['is_active']  = (bool) $request->is_active;
        $data['image']      = $request->hasFile('image') ? upload_image($request, 'image', 200, 200) : 'captain.png';
        $data['id_photo']   = upload_file($request, 'id_photo');
        $row                = User::create($data);
        $captain            = config('permission.models.role')::where('name', 'captain')->firstOrFail();
        $row->roles()->attach($captain);
        $row->services()->sync($request->services);
        return redirect()->route('captains.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row               = User::findOrFail($id);
        return view('captain::view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $row               = User::findOrFail($id);
        $selected           = $row->services()->pluck('id')->toArray();
        return view('captain::edit', compact('row', 'selected'));
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
            'email'         => 'required', 'string', 'email', Rule::unique('users', 'email')->ignore($id), 'max:255',
            'phone'         => 'required', 'string', Rule::unique('users', 'phone')->ignore($id), 'max:255',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gender'        => 'required|in:male,female',
            'id_photo'      => 'nullable|file|max:2048',
            'governorate_id'=> 'required|exists:governorates,id',
            'city_id'       => 'required|exists:cities,id',
            'services'      => 'nullable|array',
            'services.*'    => 'required|exists:services,id'
        ]);

        $data               = $request->all();
        $data['is_active']  = (bool) $request->is_active;
        $data['is_sponsored']  = (boolean) $request->is_sponsored; 
        if ($request->hasFile('image'))
            $data['image']      = upload_image($request, 'image', 200, 200);

        if ($request->hasFile('id_photo'))
            $data['id_photo']   = upload_file($request, 'id_photo');

        $row                = User::findOrFail($id);
        $row->update($data);
        $row->services()->sync($request->services);
        return redirect()->route('captains.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    public function edit_password($id)
    {
        $row               = User::findOrFail($id);
        return view('captain::password', compact('row'));
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
        return redirect()->route('captains.index')->with(['status' => 'success', 'message' => __('Password update successfully')]);
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
        return redirect()->route('captains.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
