<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;

class CaptainOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:captain_order.list')->only('index');
        $this->middleware('permission:captain_order.view')->only(['show', 'messages']);
        $this->middleware('permission:captain_order.status')->only(['status', 'post_status']);
        $this->middleware('permission:captain_order.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows = Order::where('type', 'captain')->latest()->get();
        return view('order::captain.index', compact('rows'));
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row = Order::where('type', 'captain')->where('id', $id)->firstOrFail();
        return view('order::captain.view', compact('row'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function status($id)
    {
        $row = Order::where('type', 'captain')->where('id', $id)->firstOrFail();
        return view('order::captain.status', compact('row'));
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
        $row = Order::where('type', 'captain')->where('id', $id)->firstOrFail();
        $row->update($request->all());
        return redirect()->route('captain_orders.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function messages($id)
    {
        $row = Order::where('type', 'captain')->where('id', $id)->firstOrFail();
        return view('order::captain.messages', compact('row'));
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row = Order::where('type', 'captain')->where('id', $id)->firstOrFail();
        $row->delete();
        return redirect()->route('captain_orders.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
