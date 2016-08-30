<?php

namespace App\Providers;

use View;
use Auth;
use Illuminate\Support\ServiceProvider;
Use App\Popup;

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
        View::composer(['layouts.material', 'layouts.material_shared'], function($view)
        {
            $view->laravelJsVars = base64_encode(json_encode([
                'app_url' => url('/'),
                'csrf_token' => csrf_token(),
                'user' => Auth::user(),
            ]));
        });

        View::composer('partials.popup.create_form', function($view)
        {
            if ($view->getData()['suggest_name']) {
                try {
                    $view->suggestedName = app('App\Services\AwesomeNamesSuggestor\AwesomeNamesSuggestor')
                        ->suggestor(config('popup.suggestor'))
                        ->suggestFreshRandomName(function ($name) {
                            return Popup::whereName($name)->count() === 0; // A new name is always fresh!
                        });
                } catch (\Exception $e) {
                    $view->suggestedName  = ''; // Can't suggest name...
                }
            } else {
                $view->suggestedName  = '';
            }
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
