<?php

namespace Modules\Email\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Email\Entities\Email;

class EmailController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:email.list')->only('index');
        $this->middleware('permission:email.create')->only(['create', 'store']);
        $this->middleware('permission:email.view')->only('show');
        $this->middleware('permission:email.edit')->only(['edit', 'update']);
        $this->middleware('permission:email.delete')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $rows   = Email::latest()->get();
        return view('email::index', compact('rows'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('email::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'         => 'required|string|max:255',
            'order'         => 'required|integer'
        ]);

        $data   = $request->all();
        Email::create($data);

        return redirect()->route('emails.index')->with(['status' => 'success', 'message' => __('Stored successfully')]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $row    = Email::findOrFail($id);
        return view('email::view', compact('row'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        $row    = Email::findOrFail($id);
        return view('email::edit', compact('row'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'email'         => 'required|string|max:255',
            'order'         => 'required|integer'
        ]);

        $data   = $request->all();
        $row    = Email::findOrFail($id);
        $row->update($data);

        return redirect()->route('emails.index')->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $row    = Email::findOrFail($id);
        $row->delete();

        return redirect()->route('emails.index')->with(['status' => 'success', 'message' => __('Deleted successfully')]);
    }
}
