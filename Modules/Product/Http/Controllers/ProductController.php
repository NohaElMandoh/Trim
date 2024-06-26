<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Product\Entities\Product;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:product.list')->only('index');
        $this->middleware('permission:product.create')->only(['create', 'store']);
        $this->middleware('permission:product.view')->only('show');
        $this->middleware('permission:product.edit')->only(['edit', 'update']);
        $this->middleware('permission:product.delete')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        if (auth()->user()->type == 'factories') {
            $rows = Product::where('user_id',Auth::id())->latest()->get();

        } else {
            $rows = Product::latest()->get();


        }
        return view('product::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('product::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        validate_trans($request, [
            'name' => 'required|string|max:255'
        ]);
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'order' => 'required|integer'
        ]);

        $data = $request->all();
        // $data['image']  = upload_image($request, 'image', 200, 200);
        $data['shop_id'] = 1;

        if (auth()->user()->type == 'factories') {
            $data['user_id']=Auth::id();

        }
        $row = Product::create($data);

        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/product/'; // upload path
            $photo = $request->file('image');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $row->update(['image' => 'uploads/product/' . $name]);
        }
        return redirect()->route('products.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row = Product::findOrFail($id);
        return view('product::view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $row = Product::findOrFail($id);
        return view('product::edit', compact('row'));
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
            'name' => 'required|string|max:255'
        ]);
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'order' => 'required|integer'
        ]);

        $data = $request->all();
        // if($request->hasFile('image'))
        //     $data['image']  = upload_image($request, 'image', 200, 200);
        $row = Product::findOrFail($id);
        $row->update($data);
        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/product/'; // upload path
            $photo = $request->file('image');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $row->update(['image' => 'uploads/product/' . $name]);
        }
        return redirect()->route('products.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row = Product::findOrFail($id);
        $row->delete();

        return redirect()->route('products.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
