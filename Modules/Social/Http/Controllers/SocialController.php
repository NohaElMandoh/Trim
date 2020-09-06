<?php

namespace Modules\Social\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Social\Entities\Social;

class SocialController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:social.list')->only('index');
        $this->middleware('permission:social.create')->only(['create', 'store']);
        $this->middleware('permission:social.view')->only('show');
        $this->middleware('permission:social.edit')->only(['edit', 'update']);
        $this->middleware('permission:social.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows   = Social::latest()->get();
        return view('social::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('social::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'url'           => 'required|string|url|max:255',
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'order'         => 'required|integer'
        ]);

        $data   = $request->all();
        $data['image']  = upload_image($request, 'image', 32, 32);

        Social::create($data);

        return redirect()->route('socials.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row    = Social::findOrFail($id);
        return view('social::view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $row    = Social::findOrFail($id);
        return view('social::edit', compact('row'));
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
            'url'           => 'required|string|url|max:255',
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'order'         => 'required|integer'
        ]);

        $data   = $request->all();
        if($request->hasFile('image'))
            $data['image']  = upload_image($request, 'image', 32, 32);

        $row    = Social::findOrFail($id);
        $row->update($data);

        return redirect()->route('socials.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row    = Social::findOrFail($id);
        $row->delete();

        return redirect()->route('socials.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
