<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;

class MoreWayController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:moreway_order.list')->only('index');
        $this->middleware('permission:moreway_order.view')->only(['show', 'messages']);
        $this->middleware('permission:moreway_order.status')->only(['status', 'post_status']);
        $this->middleware('permission:moreway_order.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows = Order::where('type', 'moreway')->latest()->get();
        return view('order::moreway.index', compact('rows'));
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row = Order::where('type', 'moreway')->where('id', $id)->firstOrFail();
        return view('order::moreway.view', compact('row'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function status($id)
    {
        $row = Order::where('type', 'moreway')->where('id', $id)->firstOrFail();
        return view('order::moreway.status', compact('row'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function post_status(Request $request, $id)
    {
        $request->validate([
            'status_id' => 'required|exists:statuses,id'
        ]);
        $row = Order::where('type', 'moreway')->where('id', $id)->firstOrFail();
        $row->update($request->all());
        return redirect()->route('moreway_orders.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function messages($id)
    {
        $row = Order::where('type', 'moreway')->where('id', $id)->firstOrFail();
        return view('order::moreway.messages', compact('row'));
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row = Order::where('type', 'moreway')->where('id', $id)->firstOrFail();
        $row->delete();
        return redirect()->route('moreway_orders.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
