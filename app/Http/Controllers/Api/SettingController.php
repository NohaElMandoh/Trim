<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Setting;
use App\Http\Resources\Setting as SettingResource;

class SettingController extends Controller
{
    public function index()
    {
        return response()->json(['data' => new SettingResource(Setting::firstOrFail()), 'success' => true], 200);
    }
    public function clientSettings()
    {
        return response()->json(['data' => new SettingResource(Setting::firstOrFail()), 'success' => true], 200);
    }
}
