<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:role.list')->only('index');
        $this->middleware('permission:role.create')->only(['create', 'store']);
        $this->middleware('permission:role.view')->only('show');
        $this->middleware('permission:role.edit')->only(['edit', 'update']);
        $this->middleware('permission:role.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rows      = config('permission.models.role')::latest()->get();
        return view('dashboard.roles.index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.roles.create');
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
            'name'          => 'required|string|unique:roles,name|max:255',
            'permissions.*' => 'exists:permissions,id'
        ]);
        $row               = config('permission.models.role')::create(['name' => $request->name]);
        $row->syncPermissions($request->permissions);
        return redirect()->route('roles.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $row               = config('permission.models.role')::findOrFail($id);
        return view('dashboard.roles.view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $row                   = config('permission.models.role')::findOrFail($id);
        $selected               = $row->permissions()->pluck('id')->toArray();
        return view('dashboard.roles.edit', compact('row', 'selected'));
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
            'permissions.*' => 'exists:permissions,id'
        ]);
        $row               = config('permission.models.role')::findOrFail($id);
        $row->syncPermissions($request->permissions);
        return redirect()->route('roles.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $row   = config('permission.models.role')::findOrFail($id);
        if ($row->name == 'admin' || $row->name == 'captain') {
            return redirect()->route('roles.index')->with(['status' => 'error', 'message' => __('You can\'t delete this role')]);
        }
        $row->delete();
        return redirect()->route('roles.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
