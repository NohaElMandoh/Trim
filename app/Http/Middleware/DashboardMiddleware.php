<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;

class DashboardMiddleware
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
        $permissions = [];
        if (Cache::has('permissions')) {
            $permissions = Cache::get('permissions', []);
        } else {
            $permissions = config('permission.models.permission')::pluck('id')->toArray();
            Cache::forever('permissions', $permissions);
        }
        if(auth()->user()->hasAnyPermission($permissions)) {
            return $next($request);
        } else {
            return abort(403);
        }
    }
}
