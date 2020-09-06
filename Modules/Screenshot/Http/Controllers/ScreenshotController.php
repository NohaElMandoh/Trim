<?php

namespace Modules\Screenshot\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Screenshot\Entities\Screenshot;

class ScreenshotController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:screenshot.list')->only('index');
        $this->middleware('permission:screenshot.create')->only(['create', 'store']);
        $this->middleware('permission:screenshot.view')->only('show');
        $this->middleware('permission:screenshot.edit')->only(['edit', 'update']);
        $this->middleware('permission:screenshot.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows   = Screenshot::latest()->get();
        return view('screenshot::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('screenshot::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'image'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'order'       => 'required|integer'
        ]);

        $data   = $request->all();
        $data['image']      = upload_file($request, 'image');
        Screenshot::create($data);

        return redirect()->route('screenshots.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row    = Screenshot::findOrFail($id);
        return view('screenshot::view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $row    = Screenshot::findOrFail($id);
        return view('screenshot::edit', compact('row'));
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
            'title'     => 'required|string|max:255',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'order'       => 'required|integer'
        ]);

        $data   = $request->all();
        if ($request->hasFile('image'))
            $data['image']      = upload_file($request, 'image');
        $row    = Screenshot::findOrFail($id);
        $row->update($data);

        return redirect()->route('screenshots.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row    = Screenshot::findOrFail($id);
        $row->delete();

        return redirect()->route('screenshots.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
