<?php

namespace Modules\Service\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Service\Entities\Service;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:service.list')->only('index');
        $this->middleware('permission:service.create')->only(['create', 'store']);
        $this->middleware('permission:service.view')->only('show');
        $this->middleware('permission:service.edit')->only(['edit', 'update']);
        $this->middleware('permission:service.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows   = Service::latest()->get();
        return view('service::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('service::create');
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
            'description'  => 'nullable|string|max:255',
        ]);
        $request->validate([
            'price_type'    => 'required|in:normal,range',
            'gender'        => 'required|in:male,female',
            'price'         => 'nullable|required_if:price_type,normal|numeric',
            'min_price'     => 'nullable|required_if:price_type,range|numeric',
            'max_price'     => 'nullable|required_if:price_type,range|numeric',
        ]);

        $data   = $request->all();
        $data['for_children'] = (boolean) $request->for_children;
        $data['salon_id']=$request->user()->id;

        Service::create($data);

        return redirect()->route('services.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row    = Service::findOrFail($id);
        return view('service::view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $row    = Service::findOrFail($id);
        return view('service::edit', compact('row'));
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
            'description'  => 'nullable|string|max:255',
        ]);
        $request->validate([
            'price_type'    => 'required|in:normal,range',
            'gender'        => 'required|in:male,female',
            'price'         => 'nullable|required_if:price_type,normal|numeric',
            'min_price'     => 'nullable|required_if:price_type,range|numeric',
            'max_price'     => 'nullable|required_if:price_type,range|numeric',
        ]);

        $data   = $request->all();
        $data['for_children'] = (boolean) $request->for_children;
        $data['salon_id']=$request->user()->id;
        $row    = Service::findOrFail($id);
        $row->update($data);

        return redirect()->route('services.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row    = Service::findOrFail($id);
        $row->delete();

        return redirect()->route('services.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
