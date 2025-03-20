<?php

namespace Zahzah\ApiHelper\Middlewares;

use Zahzah\ApiHelper\Facades;
use Closure;

class ApiAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    { 
        /**
         * HEADER WITH TOKEN
         * - Authorization : Bearer <token>
         * - AppCode       : <app_code>
         * 
         * HEADER WITHOUT TOKEN 
         * - Authorization : Bearer <username dan password> ketika generate token
         * - AppCode   : <app_code> 
         */
        Facades\ApiAccess::init();
        return $next($request);
    }
}
