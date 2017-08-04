<?php

namespace App\Jobs;

use Auth;
use Illuminate\Bus\Queueable;
use App\Http\Controllers\AmazonTraits;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncAmazonItems implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, AmazonTraits\SyncAmazonItems;

    protected $user;
    protected $siteID;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $siteID)
    {
        //
        $this->user = $user;
        $this->siteID = $siteID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->SyncAmazonItems($this->user, $this->siteID);
    }
}
