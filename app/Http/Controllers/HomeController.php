<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Modules\Career\Entities\Career;
use Modules\Career\Notifications\NewCareerNotification;
use Modules\Feature\Entities\Feature;
use Modules\Screenshot\Entities\Screenshot;
use Modules\Subscription\Entities\Subscription;

class HomeController extends Controller
{

    /**
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function home()
    {
        $features = Feature::orderBy('order')->get();
        $screenshots = Screenshot::orderBy('order')->get();
        $subscriptions=Subscription::get();
        return view('front.home', compact('features', 'screenshots','subscriptions'));
    }

    /**
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function career()
    {
        return view('front.career');
    }

    public function save_career(Request $request) {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|max:255',
            'phone'     => 'required|string|max:255',
            'job'       => 'required|string|max:255',
            'cv'        => 'required|file||max:2048',
        ]);
        $data = $request->all();
        $data['cv'] = upload_file($request, 'cv');
        $career = Career::create($data);
        $users = User::permission('career.list')->get();
        Notification::send($users, new NewCareerNotification($career));
        return redirect()->route('career')->with(['status' => 'success', 'message' => __('messages.We will contact you ASAP')]);
    }

        /**
     * Show the file.
     *
     * @return \Illuminate\Http\Response
     */
    public function file_show($filename = null)
    {
        return response()->file(storage_path('app/public/' . $filename));
    }
}
