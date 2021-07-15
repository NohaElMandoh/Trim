<?php

namespace Modules\Subscription\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Subscription\Entities\Subscription;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $rows   = Subscription::latest()->get();
        return view('subscription::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('subscription::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        validate_trans($request, [
            'title'  => 'required|string|max:255',
            'desc'=> 'required',
        ]);

        $request->validate([
            'price'          => 'required',
            'origion-price' => 'required',
            'months'   => 'required',
            'currency'         => 'required',

        ]);
  
        $data   = $request->all();
        Subscription::create($data);

        return redirect()->route('subscription.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $row               = Subscription::findOrFail($id);
        return view('subscription::view', compact('row'));

        // return view('subscription::view');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        $row               = Subscription::findOrFail($id);
        return view('subscription::edit', compact('row'));

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        validate_trans($request, [
            'title'  => 'required|string|max:255'
        ]);

        $request->validate([
            'price'          => 'required',
            'months'   => 'required',
            'currency'         => 'required',

        ]);
        $data               = $request->all();
        $row                = Subscription::findOrFail($id);
        $row->update($data);

        return redirect()->route('subscription.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $row    = Subscription::findOrFail($id);
        $row->delete();

        return redirect()->route('subscription.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
