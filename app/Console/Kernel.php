<?php

namespace App\Console;

use Auth;
use App\User;
use App\Test;
use App\BulkFile;
use App\Jobs\TestJob;
use App\Jobs\SyncEbayOrders; // keep till command
use App\Jobs\CleanSpierItems;
use App\Jobs\SyncAllUsersOrders;
use App\Jobs\SyncAllUsersStores;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Bus\DispatchesJobs; // keep till command
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    use DispatchesJobs; // keep till command
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->everyMinute();

        $schedule->call(function ()
        {
            $job = (new SyncAllUsersOrders())->onQueue('sellerpier');
            dispatch($job);
            
        })->everyThirtyMinutes();

        $schedule->call(function ()
        {
            $job = (new SyncAllUsersStores())->onQueue('sellerpier');
            dispatch($job);
              
        })->dailyAt('4:00');
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
