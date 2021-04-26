<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmailResource;
use App\Http\Resources\PhoneResource;
use Illuminate\Http\Request;
use App\Setting;
use App\Http\Resources\Setting as SettingResource;
use Modules\Email\Entities\Email;
use Modules\Phone\Entities\Phone;

class SettingController extends Controller
{
    public function index()
    {
        return response()->json(['data' => new SettingResource(Setting::firstOrFail()), 'success' => true], 200);
    }
    public function contacts()
    {
        $emails = Email::all();
        $phones = Phone::all();
        return response()->json(['data' => ['emails' => EmailResource::collection($emails), 'phones' => PhoneResource::collection($phones)], 'success' => true], 200);
    }
}
