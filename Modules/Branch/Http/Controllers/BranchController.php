<?php

namespace Modules\Branch\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Branch\Entities\Branch;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:branch.list')->only('index');
        $this->middleware('permission:branch.create')->only(['create', 'store']);
        $this->middleware('permission:branch.view')->only('show');
        $this->middleware('permission:branch.edit')->only(['edit', 'update']);
        $this->middleware('permission:branch.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows   = Branch::latest()->get();
        return view('branch::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('branch::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        validate_trans($request, [
            'address'  => 'required|string|max:255'
        ]);
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'lat'           => 'required|numeric',
            'lng'           => 'required|numeric'
        ]);

        $data   = $request->all();
        Branch::create($data);

        return redirect()->route('branches.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row    = Branch::findOrFail($id);
        return view('branch::view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $row    = Branch::findOrFail($id);
        return view('branch::edit', compact('row'));
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
            'address'  => 'required|string|max:255'
        ]);
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'lat'           => 'required|numeric',
            'lng'           => 'required|numeric'
        ]);

        $data   = $request->all();
        $row    = Branch::findOrFail($id);
        $row->update($data);

        return redirect()->route('branches.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row    = Branch::findOrFail($id);
        $row->delete();

        return redirect()->route('branches.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
