<?php

namespace Modules\Lesson\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Lesson\Entities\Lesson;

class LessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:lesson.list')->only('index');
        $this->middleware('permission:lesson.create')->only(['create', 'store']);
        $this->middleware('permission:lesson.view')->only('show');
        $this->middleware('permission:lesson.edit')->only(['edit', 'update']);
        $this->middleware('permission:lesson.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows   = Lesson::latest()->get();
        return view('lesson::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('lesson::create');
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
        ]);
        $request->validate([
            'image'         => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'video'         => 'required|file|max:2048',
            'order'         => 'required|integer',
            'course_id'     => 'required|exists:courses,id'
        ]);

        $data   = $request->all();
        $data['image']  = upload_image($request, 'image', 400, 400);
        $data['video']  = upload_file($request, 'video');
        Lesson::create($data);

        return redirect()->route('lessons.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row    = Lesson::findOrFail($id);
        return view('lesson::view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $row    = Lesson::findOrFail($id);
        return view('lesson::edit', compact('row'));
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
        ]);
        $request->validate([
            'image'         => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'video'         => 'nullable|file|max:2048',
            'order'         => 'required|integer',
            'course_id'     => 'required|exists:courses,id'
        ]);

        $data   = $request->all();
        if($request->hasFile('image'))
            $data['image']  = upload_image($request, 'image', 400, 400);

        if($request->hasFile('video'))
            $data['video']  = upload_file($request, 'video');
        $row    = Lesson::findOrFail($id);
        $row->update($data);

        return redirect()->route('lessons.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row    = Lesson::findOrFail($id);
        $row->delete();

        return redirect()->route('lessons.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
