<?php

namespace App\Http\Middleware;

use App\Setting;
use Closure;
use Illuminate\Support\Facades\Cache;

class SettingsShare
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
        $settings = null;
        if(Cache::has('settings')) {
            $settings   = Cache::get('settings', null);
        } else {
            $settings   = Setting::firstOrFail();
            Cache::put('settings', $settings, now()->addMinutes(10));
        }

        view()->share('settings', $settings);
        
        return $next($request);
    }
}
