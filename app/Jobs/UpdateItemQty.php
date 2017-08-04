<?php

namespace App\Jobs;

use App\Ebay;
use App\Amazon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class UpdateItemQty implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $spierItem;
    protected $except;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($spierItem, $except = null)
    {
        $this->spierItem = $spierItem;
        $this->except = $except;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $this->updateLocal($this->spierItem);
    }

    public function updateLocal($localItem)
    {
        foreach ($localItem->marketPlaceItems as $item) 
        {
            if ($item->integration->qtySync) 
            {
                $item->update(['quantityAvailable' => $localItem->quantityAvailable]);

                // $this->updateLive($item, $localItem->quantityAvailable);
            }
        }
    }

    public function updateLive($item, $qty)
    {
        switch ($item->integration->marketPlace) 
        {
            case 'Amazon':
                Amazon::updateQuantity($this->spierItem->user, $item->integration->siteID, $item->sku, $qty);
                break;

            case 'Ebay':
                Ebay::updateQuantity($this->spierItem->user, $item->integration->siteID, $item->marketPlaceItemID, $qty);
                break;
            
            // rest of integrations
        }
    }
}