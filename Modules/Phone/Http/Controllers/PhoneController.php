<?php

namespace Modules\Phone\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Phone\Entities\Phone;

class PhoneController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:phone.list')->only('index');
        $this->middleware('permission:phone.create')->only(['create', 'store']);
        $this->middleware('permission:phone.view')->only('show');
        $this->middleware('permission:phone.edit')->only(['edit', 'update']);
        $this->middleware('permission:phone.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows   = Phone::latest()->get();
        return view('phone::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('phone::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'phone'         => 'required|string|max:255',
            'order'         => 'required|integer'
        ]);

        $data   = $request->all();
        Phone::create($data);

        return redirect()->route('phones.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row    = Phone::findOrFail($id);
        return view('phone::view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $row    = Phone::findOrFail($id);
        return view('phone::edit', compact('row'));
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
            'phone'         => 'required|string|max:255',
            'order'         => 'required|integer'
        ]);

        $data   = $request->all();
        $row    = Phone::findOrFail($id);
        $row->update($data);

        return redirect()->route('phones.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row    = Phone::findOrFail($id);
        $row->delete();

        return redirect()->route('phones.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
