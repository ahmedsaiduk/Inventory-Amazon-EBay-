<?php

namespace App\Listeners;

use App\Setting;
use App\Integration;
use Illuminate\Auth\Events\Registered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class InitSettings
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        $setting = new Setting;
        
        $event->user->setting()->save($setting);
        $event->user->subscribe();
    }
}
