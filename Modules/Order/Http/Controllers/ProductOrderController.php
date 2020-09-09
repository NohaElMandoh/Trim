<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;

class ProductOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:product_order.list')->only('index');
        $this->middleware('permission:product_order.view')->only(['show', 'messages']);
        $this->middleware('permission:product_order.status')->only(['status', 'post_status']);
        $this->middleware('permission:product_order.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows = Order::where('type', 'product')->latest()->get();
        return view('order::product.index', compact('rows'));
    }


    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row = Order::where('type', 'product')->where('id', $id)->firstOrFail();
        return view('order::product.view', compact('row'));
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function status($id)
    {
        $row = Order::where('type', 'product')->where('id', $id)->firstOrFail();
        return view('order::product.status', compact('row'));
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
        $row = Order::where('type', 'product')->where('id', $id)->firstOrFail();
        $row->update($request->all());
        return redirect()->route('product_orders.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }


    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row = Order::where('type', 'product')->where('id', $id)->firstOrFail();
        $row->delete();
        return redirect()->route('product_orders.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
