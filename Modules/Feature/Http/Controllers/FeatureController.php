<?php

namespace Modules\Feature\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Feature\Entities\Feature;

class FeatureController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:feature.list')->only('index');
        $this->middleware('permission:feature.create')->only(['create', 'store']);
        $this->middleware('permission:feature.view')->only('show');
        $this->middleware('permission:feature.edit')->only(['edit', 'update']);
        $this->middleware('permission:feature.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows   = Feature::latest()->get();
        return view('feature::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('feature::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        validate_trans($request, [
            'title'  => 'required|string|max:255'
        ]);
        $request->validate([
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'order'       => 'required|integer'
        ]);

        $data   = $request->all();
        $data['image']      = upload_image($request, 'image', 30, 30);
        Feature::create($data);

        return redirect()->route('features.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row    = Feature::findOrFail($id);
        return view('feature::view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $row    = Feature::findOrFail($id);
        return view('feature::edit', compact('row'));
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
            'title'  => 'required|string|max:255'
        ]);
        $request->validate([
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'order'       => 'required|integer'
        ]);

        $data   = $request->all();
        if($request->hasFile('image'))
            $data['image']      = upload_image($request, 'image', 30, 30);
        $row    = Feature::findOrFail($id);
        $row->update($data);

        return redirect()->route('features.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row    = Feature::findOrFail($id);
        $row->delete();

        return redirect()->route('features.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
