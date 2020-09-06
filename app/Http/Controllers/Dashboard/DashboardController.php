<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Token;
use App\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Main page for dashboard
     */
    public function index()
    {
        return view('dashboard.index');
    }

    public function send_notification(Request $request)
    {
        $request->validate([
            'name'          => 'nullable|string|max:255',
            'type'          => 'required|in:user_app,captain_app',
            'title'         => 'required|string|max:255',
            'description'   => 'nullable|string|max:255',
        ]);
        
        if ($request->name) {
            $tokens = Token::where('type', $request->type)->whereHas('user', function ($query) use($request) {
                $query->where('name', 'LIKE', '%' . $request->name . '%');
            })->get()->pluck('token');
            send_notif($request->title, ['url' => '', 'event' => 'notification', 'notif_id' => ''], $request->description, $tokens);
        } else {
            $tokens = Token::where('type', $request->type)->get()->pluck('token');
            send_notif($request->title, ['url' => '', 'event' => 'notification', 'notif_id' => ''], $request->description, $tokens);
        }
        return redirect()->route('dashboard')->with(['status' => 'success', 'message' => __('Notification sent')]);
    }
}
