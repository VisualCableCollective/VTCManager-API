<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class EnsureUserHasLicenseKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()) {
            return response("", 401);
        }

        if (!User::with("license_key")->find($request->user()->id)->license_key) {
            return response(["success" => false, "error" => "NO_LICENSE_KEY"], 403);
        }
        return $next($request);
    }
}
