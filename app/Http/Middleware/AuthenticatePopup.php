<?php

namespace App\Http\Middleware;

use Route;
use Closure;
use Auth;

class AuthenticatePopup
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
        config()->set('auth.defaults.guard', 'popup');

        $params = Route::current()->parameters();

        if (isset($params['popup']) && isset($params['secret'])) {
            Auth::once([
                'name'     => $params['popup'],
                'password' => $params['secret']
            ]);
        }

        if (Auth::guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else if (isset($params['popup'])) {
                return redirect()->guest('popup/' . $params['popup']);
            } else {
                return redirect()->guest('login');
            }
        }

        return $next($request);
    }
}
