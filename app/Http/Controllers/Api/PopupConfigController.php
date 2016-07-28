<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Dingo\Api\Routing\Helpers;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Popup;

class PopupConfigController extends Controller
{
    use Helpers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('cors');
    }

    /**
     * Update popup.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string                    $name
     * @return \Illuminate\Http\Response
     */
    public function getConfig(Request $request, $name)
    {
        $popup = Popup::whereName($name)->first();

        // Popup doesn't exist
        if (is_null($popup)) {
            throw new NotFoundHttpException("Popup '{$name}' not found. Create a new popup at " . url('/'));
        }

        // Check popup origin when CORS
        if (app('Barryvdh\Cors\Stack\CorsService')->isCorsRequest($request)) {
            if (!$this->checkPopupOrigin($request, $popup)) {
                throw new AccessDeniedHttpException("Popup '{$name}' has an invalid or not configured domain. Fix-it at " . url("/popup/{$name}"));
            }
        }

        // le php convert empty array to [] but js love {}
        if (empty($popup->config)) {
            return '{}';
        }

        return $popup->config;
    }

    /**
     * Check popup origin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Popup                $popup
     * @return boolean
     */
    protected function checkPopupOrigin(Request $request, $popup)
    {
        $origin = $request->headers->get('Origin');
        $domain = $popup->domain;

        if (empty($domain)) {
            return false;
        }

        $allowedOrigins = ["http://{$domain}", "https://{$domain}"];
        return in_array($origin, $allowedOrigins);
    }
}
