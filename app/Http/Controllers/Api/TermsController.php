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

class TermsController extends Controller
{
   
    public function salon_terms()
    {
        $terms = __('terms.salon_terms');
        return response()->json(['data' => $terms, 'success' => true], 200);
    }
    public function user_terms()
    {
        $terms = __('terms.user_terms');
        return response()->json(['data' => $terms, 'success' => true], 200);
    }
}
