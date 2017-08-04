<?php

namespace App\Http\Controllers;

use App\Jobs\SyncEbayStore;
use App\Jobs\SyncEbayOrders;
use App\Jobs\SyncAmazonItems;
use App\Jobs\CleanSpierItems;
use App\Jobs\SyncAmazonOrders;
use Illuminate\Foundation\Bus\DispatchesJobs;

trait RefreshIntegrationTrait
{	
	use DispatchesJobs; 

	protected function RefreshIntegration($integration) // with orders
	{
		switch ($integration->marketPlace) 
        {
            case 'Amazon':
                $job = (new SyncAmazonItems($integration->user, $integration->siteID))->onQueue('sellerpier');
                dispatch($job);
                
                $job = (new SyncAmazonOrders($integration->user, $integration->siteID, true))->onQueue('sellerpier');
                dispatch($job);
                
                break;
                
            case 'Ebay':
                $job = (new SyncEbayStore($integration->user, $integration->siteID))->onQueue('sellerpier');
                dispatch($job);
                
                $job = (new SyncEbayOrders($integration->user, $integration->siteID, true))->onQueue('sellerpier');
                dispatch($job);
                
                break;
        }
	}
}