<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\AccountExpiredException;

class CheckAccountExpiration
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
        if (Auth::check()) {

            if (Auth::user()->isAgency()) {
                if (Auth::user()->agencyAccount->isExpired()) {
                    throw new AccountExpiredException();
                }
            }

            if (Auth::user()->isCustomer()) {
                if (Auth::user()->customerAccount->isExpired()) {
                    throw new AccountExpiredException();
                }
            }
        }

        return $next($request);
    }
}
