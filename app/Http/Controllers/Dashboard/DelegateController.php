<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;

class DelegateController extends Controller
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

        $rows = User::where('type', 'delegates')->latest()->get();
        return view('dashboard.delegates.index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.delegates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email|max:255',
            'phone' => 'required|string|unique:users,phone|max:255',

            'password' => 'required|string|min:6|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();
        $data['type'] = 'delegates';

        $data['password'] = bcrypt($request->password);
        $data['is_active'] = (bool)$request->is_active;
//        $data['image']      = $request->hasFile('image') ? upload_image($request, 'image', 200, 200) : 'user.png';


        $row = User::create($data);

        $row->roles()->attach($request->roles);

        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/salon/'; // upload path
            $photo = $request->file('image');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $row->update(['image' => 'uploads/salon/' . $name]);
        }
        return redirect()->route('delegates.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $row = User::findOrFail($id);

        return view('dashboard.delegates.view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row = User::findOrFail($id);
        return view('dashboard.delegates.edit', compact('row'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'phone' => 'required|string|max:255',

            'password' => 'required|string|min:6|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $phone = '+2' . $request->phone;
        $user_with_phone = User::where('phone', $phone)->first();
        if ($user_with_phone) {
            return response()->json(['success' => false, 'message' => __('messages.phone taken before')], 400);
        }
        $data = $request->all();
        $data['is_active'] = (bool)$request->is_active;
        // if ($request->hasFile('image'))
        //     $data['image']      = upload_image($request, 'image', 200, 200);

        $row = User::findOrFail($id);
        $row->update($data);
//        $row->roles()->sync($request->roles);
        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/salon/'; // upload path
            $photo = $request->file('image');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $row->update(['image' => 'uploads/salon/' . $name]);
        }
        return redirect()->route('delegates.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    public function edit_password($id)
    {
        $row = User::findOrFail($id);
        return view('dashboard.delegates.password', compact('row'));
    }

    public function update_password(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:6|max:255',
        ]);
        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $row = User::findOrFail($id);
        $row->update($data);
        return redirect()->route('delegates.index')->with(['status' => 'success', 'message' => __('Password update successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $row = User::findOrFail($id);
        $row->delete();
        return redirect()->route('delegates.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
