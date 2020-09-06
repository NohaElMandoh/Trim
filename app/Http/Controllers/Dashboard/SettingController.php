<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:setting.view')->only('index');
        $this->middleware('permission:setting.edit')->only('update');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $site = Setting::firstOrFail();
        return view('dashboard.settings.index', compact('site'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        validate_trans($request, [
            ['title', 'required|string|max:255'],
            ['description', 'required|string|max:255'],
            ['copyrights', 'required|string|max:255'],
            ['privacy', 'nullable|string'],
            ['how_it_works', 'nullable|string'],
            ['work_in_oq', 'nullable|string'],
        ]);
        $request->validate([
            'point_price'   => 'required|numeric',
            'google_play_user_app' => 'nullable|string|max:255',
            'google_play_captain_app' => 'nullable|string|max:255',
            'app_store_user_app' => 'nullable|string|max:255',
            'app_store_captain_app' => 'nullable|string|max:255',
            'header_logo'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'google_play_logo'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'app_store_logo'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'header_screenshot'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'app_features_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'delivery_image'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'icon'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'logo'    => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $site           = Setting::firstOrFail();
        $data           = $request->all();
        if ($request->hasFile('header_logo'))
            $data['header_logo']   = upload_image($request, 'header_logo', 55, 55);

        if ($request->hasFile('google_play_logo'))
            $data['google_play_logo']   = upload_image($request, 'google_play_logo', 170, 60);

        if ($request->hasFile('app_store_logo'))
            $data['app_store_logo']   = upload_image($request, 'app_store_logo', 170, 60);

        if ($request->hasFile('header_screenshot'))
            $data['header_screenshot']   = upload_image($request, 'header_screenshot', 350, 550);

        if ($request->hasFile('app_features_image'))
            $data['app_features_image']   = upload_image($request, 'app_features_image', 500, 450);

        if ($request->hasFile('delivery_image'))
            $data['delivery_image']   = upload_image($request, 'delivery_image', 350, 350);

        if ($request->hasFile('logo'))
            $data['logo']   = upload_image($request, 'logo', 200, 200);

        if ($request->hasFile('icon'))
            $data['icon']       = upload_image($request, 'icon', 16, 16);
        $site->update($data);

        Cache::forget('settings');

        return redirect()->route('settings.index')
            ->with(['status' => 'success', 'message' => __('Updated successfully')]);
    }
}
