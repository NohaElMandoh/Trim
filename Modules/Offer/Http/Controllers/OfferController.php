<?php

namespace Modules\Offer\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Offer\Entities\Offer;

class OfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:offer.list')->only('index');
        $this->middleware('permission:offer.create')->only(['create', 'store']);
        $this->middleware('permission:offer.view')->only('show');
        $this->middleware('permission:offer.edit')->only(['edit', 'update']);
        $this->middleware('permission:offer.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows   = Offer::latest()->get();
        return view('offer::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('offer::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        validate_trans($request, [
            'name'  => 'required|string|max:255',
            'description'  => 'required|string|max:255',
        ]);
        $request->validate([
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id'       => 'required|exists:users,id',
            'price'         => 'required|numeric',
        ]);

        $data   = $request->all();
        $data['image']  = upload_image($request, 'image', 800, 400);
        $data['is_sponsored']  = (boolean) $request->is_sponsored; 
        Offer::create($data);

        return redirect()->route('offers.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row    = Offer::findOrFail($id);
        return view('offer::view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $row    = Offer::findOrFail($id);
        return view('offer::edit', compact('row'));
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
            'name'  => 'required|string|max:255',
            'description'  => 'required|string|max:255',
        ]);
        $request->validate([
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'user_id'       => 'required|exists:users,id',
            'price'         => 'required|numeric',
        ]);

        $data   = $request->all();
        if($request->hasFile('image'))
            $data['image']  = upload_image($request, 'image', 800, 400);

        $data['is_sponsored']  = (boolean) $request->is_sponsored; 
        $row    = Offer::findOrFail($id);
        $row->update($data);

        return redirect()->route('offers.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row    = Offer::findOrFail($id);
        $row->delete();

        return redirect()->route('offers.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
