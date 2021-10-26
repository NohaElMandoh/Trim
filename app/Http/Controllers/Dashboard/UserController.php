<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:user.list')->only('index');
        $this->middleware('permission:user.create')->only(['create', 'store']);
        $this->middleware('permission:user.view')->only('show');
        $this->middleware('permission:user.edit')->only(['edit', 'update', 'edit_password', 'update_password']);
        $this->middleware('permission:user.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $rows      = User::where('type', 'admin')->whereDoesntHave('roles', function ($query) {
        //     $query->where('name', 'salon')->orWhere('name', 'captain');
        // })->latest()->get();
        $rows = User::where('type', 'admin')->orWhere('type', 'user')->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'salon')->orWhere('name', 'captain');
        })->latest()->get();
        return view('dashboard.users.index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles      = config('permission.models.role')::where('name', '!=', 'salon')->where('name', '!=', 'captain')->latest()->get();
        return view('dashboard.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|string|email|unique:users,email|max:255',
            'phone'         => 'required|string|unique:users,phone|max:255',
            'password'      => 'required|string|min:6|max:255',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gender'        => 'required|in:male,female',
            'roles.*'       => 'exists:roles,id',
            'governorate_id'=> 'required|exists:governorates,id',
            'city_id'       => 'required|exists:cities,id',
        ]);

        $data               = $request->all();
        // $data['type']   ='admin';
       
        $data['type']   ='user';
        $data['phone']  = '+2' . $request->phone;
        $data['password']   = bcrypt($request->password);
        $data['is_active']  = (bool) $request->is_active;
//        $data['image']      = $request->hasFile('image') ? upload_image($request, 'image', 200, 200) : 'user.png';

        $row               = User::create($data);
        $row->roles()->attach($request->roles);

        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/user/'; // upload path
            $photo = $request->file('image');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $row->update(['image' => 'uploads/user/' . $name]);
        }
        return redirect()->route('users.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $row               = User::findOrFail($id);
        return view('dashboard.users.view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles              = config('permission.models.role')::where('name', '!=', 'salon')->where('name', '!=', 'captain')->latest()->get();
        $row                = User::findOrFail($id);
        $selected           = $row->roles()->pluck('id')->toArray();
        return view('dashboard.users.edit', compact('roles', 'row', 'selected'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required', 'string', 'email', Rule::unique('users', 'email')->ignore($id), 'max:255',
            'phone'         => 'required', 'string', Rule::unique('users', 'phone')->ignore($id), 'max:255',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'gender'        => 'required|in:male,female',
            'roles.*'       => 'exists:roles,id',
            'governorate_id'=> 'required|exists:governorates,id',
            'city_id'       => 'required|exists:cities,id',
        ]);
        $phone = '+2' . $request->phone;
        $user_with_phone = User::where('phone', $phone)->first();
        if ($user_with_phone) {
            return response()->json(['success' => false, 'message' => __('messages.phone taken before')], 400);
        }
        $data               = $request->all();
        $data['is_active']  = (bool) $request->is_active;
        // if ($request->hasFile('image'))
        //     $data['image']      = upload_image($request, 'image', 200, 200);

        $row               = User::findOrFail($id);
        $row->update($data);
        $row->roles()->sync($request->roles);
        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/user/'; // upload path
            $photo = $request->file('image');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $row->update(['image' => 'uploads/user/' . $name]);
        }
        return redirect()->route('users.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    public function edit_password($id)
    {
        $row               = User::findOrFail($id);
        return view('dashboard.users.password', compact('row'));
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
        return redirect()->route('users.index')->with(['status' => 'success', 'message' => __('Password update successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $row   = User::findOrFail($id);
        $row->delete();
        return redirect()->route('users.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
