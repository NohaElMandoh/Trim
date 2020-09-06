<?php

namespace Modules\Address\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Address\Entities\Address;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:address.list')->only('index');
        $this->middleware('permission:address.create')->only(['create', 'store']);
        $this->middleware('permission:address.view')->only('show');
        $this->middleware('permission:address.edit')->only(['edit', 'update']);
        $this->middleware('permission:address.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows   = Address::latest()->get();
        return view('address::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('address::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        validate_trans($request, [
            ['address', 'required|string|max:255'],
        ]);
        $request->validate([
            "lat"       => 'required|numeric',
            "lng"       => 'required|numeric',
            'order'     => 'required|integer',
        ]);

        Address::create($request->all());
        return redirect()->route('addresses.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row    = Address::findOrFail($id);
        return view('address::view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $row    = Address::findOrFail($id);
        return view('address::edit', compact('row'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        validate_trans($request, [
            ['address', 'required|string|max:255'],
        ]);
        $request->validate([
            "lat"       => 'required|numeric',
            "lng"       => 'required|numeric',
            'order'     => 'required|integer',
        ]);

        $row            = Address::findOrFail($id);
        $row->update($request->all());
        return redirect()->route('addresses.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row            = Address::findOrFail($id);
        $row->delete();
        return redirect()->route('addresses.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
