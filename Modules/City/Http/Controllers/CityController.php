<?php

namespace Modules\City\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\City\Entities\City;

class CityController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:city.list')->only('index');
        $this->middleware('permission:city.create')->only(['create', 'store']);
        $this->middleware('permission:city.view')->only('show');
        $this->middleware('permission:city.edit')->only(['edit', 'update']);
        $this->middleware('permission:city.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows   = City::latest()->get();
        return view('city::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('city::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        validate_trans($request, [
            'name'  => 'required|string|max:255'
        ]);
        $request->validate([
            'governorate_id'    => 'required|exists:governorates,id',
        ]);
        
        $data   = $request->all();
        City::create($data);

        return redirect()->route('cities.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row    = City::findOrFail($id);
        return view('city::view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $row    = City::findOrFail($id);
        return view('city::edit', compact('row'));
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
            'name'  => 'required|string|max:255'
        ]);
        $request->validate([
            'governorate_id'    => 'required|exists:governorates,id',
        ]);

        $data   = $request->all();
        $row    = City::findOrFail($id);
        $row->update($data);

        return redirect()->route('cities.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row    = City::findOrFail($id);
        $row->delete();

        return redirect()->route('cities.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
