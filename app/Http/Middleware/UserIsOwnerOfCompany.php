<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UserIsOwnerOfCompany
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
        if($request->user()->id != $request->user()->company->owner_id){
            abort(401);
        }

        return $next($request);
    }
}
