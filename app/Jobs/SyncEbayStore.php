<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use App\Http\Controllers\EbayTraits;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SyncEbayStore implements ShouldQueue
{
    use Queueable, 
        Dispatchable,
        SerializesModels,
        InteractsWithQueue, 
        EbayTraits\SyncEbayItems,
        EbayTraits\SyncEbayCategories; 

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
        $this->SyncEbayCategories($this->user, $this->siteID);      
        $this->SyncEbayItems($this->user, $this->siteID);       
    }
}
