<?php

namespace Modules\Governorate\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Governorate\Entities\Governorate;

class GovernorateController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:governorate.list')->only('index');
        $this->middleware('permission:governorate.create')->only(['create', 'store']);
        $this->middleware('permission:governorate.view')->only('show');
        $this->middleware('permission:governorate.edit')->only(['edit', 'update']);
        $this->middleware('permission:governorate.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows   = Governorate::latest()->get();
        return view('governorate::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('governorate::create');
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

        $data   = $request->all();
        Governorate::create($data);

        return redirect()->route('governorates.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row    = Governorate::findOrFail($id);
        return view('governorate::view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $row    = Governorate::findOrFail($id);
        return view('governorate::edit', compact('row'));
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

        $data   = $request->all();
        $row    = Governorate::findOrFail($id);
        $row->update($data);

        return redirect()->route('governorates.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row    = Governorate::findOrFail($id);
        $row->delete();

        return redirect()->route('governorates.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
