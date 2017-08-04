<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Http\Controllers\EbayTraits;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncEbayOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, EbayTraits\SyncEbayOrders;

    protected $user;
    protected $siteID;
    protected $initial;
    protected $startDate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $siteID, $initial = false, $startDate = null)
    {
        $this->user = $user;
        $this->siteID = $siteID;
        $this->initial = $initial;
        $this->startDate = $startDate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->SyncEbayOrders($this->user, $this->siteID, $this->initial, $this->startDate);
    }
}
