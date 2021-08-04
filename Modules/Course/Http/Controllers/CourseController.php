<?php

namespace Modules\Course\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Course\Entities\Course;

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:course.list')->only('index');
        $this->middleware('permission:course.create')->only(['create', 'store']);
        $this->middleware('permission:course.view')->only('show');
        $this->middleware('permission:course.edit')->only(['edit', 'update']);
        $this->middleware('permission:course.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows   = Course::latest()->get();
        return view('course::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('course::create');
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
            'description' => 'nullable|string'
        ]);
        $request->validate([
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price'         => 'required|numeric',
            'order'         => 'required|integer'
        ]);

        $data   = $request->all();
        // $data['image']  = upload_image($request, 'image', 400, 400);
      
       $row= Course::create($data);
       if ($request->hasFile('image')) {
        $path = public_path();
        $destinationPath = $path . '/uploads/courses/'; // upload path
        $photo = $request->file('image');
        $extension = $photo->getClientOriginalExtension(); // getting image extension
        $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
        $photo->move($destinationPath, $name); // uploading file to given path
        $row->update(['image' => 'uploads/courses/' . $name]);
    }
        return redirect()->route('courses.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row    = Course::findOrFail($id);
        return view('course::view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $row    = Course::findOrFail($id);
        return view('course::edit', compact('row'));
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
            'description' => 'nullable|string'
        ]);
        $request->validate([
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'price'         => 'required|numeric',
            'order'         => 'required|integer'
        ]);

        $data   = $request->all();
        // if($request->hasFile('image'))
        //     $data['image']  = upload_image($request, 'image', 400, 400);
        $row    = Course::findOrFail($id);
        $row->update($data);
        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/courses/'; // upload path
            $photo = $request->file('image');
            $extension = $photo->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renameing image
            $photo->move($destinationPath, $name); // uploading file to given path
            $row->update(['image' => 'uploads/courses/' . $name]);
        }
        return redirect()->route('courses.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row    = Course::findOrFail($id);
        $row->delete();

        return redirect()->route('courses.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
