<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
class ReadNotification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->has('notif_id') && Auth::check()){
            if( \Illuminate\Support\Facades\Auth::user()->notifications()->where('id', $request->notif_id)->where('read_at', null)->first())
                Auth::user()->notifications()->where('id', $request->notif_id)->first()->markAsRead();
        }
        return $next($request);
    }
}
