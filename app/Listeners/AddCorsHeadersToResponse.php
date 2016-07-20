<?php

namespace App\Listeners;

use Illuminate\Http\Request;
use Dingo\Api\Event\ResponseWasMorphed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AddCorsHeadersToResponse
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Handle the event.
     *
     * @param  ResponseWasMorphed  $event
     * @return void
     */
    public function handle(ResponseWasMorphed $event)
    {
        // Got an error reponse, add Cors headers...
        if ($event->response->status() >= 400) {
            app('Barryvdh\Cors\Stack\CorsService')->addActualRequestHeaders($event->response, $this->request);
        }
    }
}
