<?php

namespace Modules\Category\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Category\Entities\Category;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:category.list')->only('index');
        $this->middleware('permission:category.create')->only(['create', 'store']);
        $this->middleware('permission:category.view')->only('show');
        $this->middleware('permission:category.edit')->only(['edit', 'update']);
        $this->middleware('permission:category.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows = Category::latest()->get();
        return view('category::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('category::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        validate_trans($request, [
            'name'  => 'required|string|max:255'
        ]);
        $request->validate([
            'image' => 'required|image|max:2048',
            'order' => 'required|integer'
        ]);

        $data               = $request->all();
        $data['image']      = upload_image($request, 'image', 200, 200);
        $row                = Category::create($data);
        return redirect()->route('categories.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row               = Category::findOrFail($id);
        return view('category::view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $row               = Category::findOrFail($id);
        return view('category::edit', compact('row'));
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
            'name'  => 'required|string|max:255'
        ]);

        $request->validate([
            'image' => 'nullable|image|max:2048',
            'order' => 'required|integer'
        ]);

        $data               = $request->all();
        if($request->hasFile('image'))
            $data['image']      = upload_image($request, 'image', 200, 200);
        $row                = Category::findOrFail($id);
        $row->update($data);
        return redirect()->route('categories.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row   = Category::findOrFail($id);
        $row->delete();
        return redirect()->route('categories.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
