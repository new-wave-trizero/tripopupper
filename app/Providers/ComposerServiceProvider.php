<?php

namespace App\Providers;

use View;
use Auth;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // TODO: Maybe make a little piece of view with this data...
        View::composer('layouts.material', function($view)
        {
            $view->laravelJsVars = base64_encode(json_encode([
                'app_url' => url('/'),
                'csrf_token' => csrf_token(),
                'user' => Auth::user(),
            ]));
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
