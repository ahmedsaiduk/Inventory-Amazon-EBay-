<?php

namespace App\Http\Controllers\AmazonTraits;

use App\Amazon;
use App\SPierItem;
use App\MarketPlaceItem;

trait SyncAmazonItems
{    
	protected function SyncAmazonItems($user, $siteID)
	{
		$products = Amazon::getAllItems($user, $siteID);

        if ($products) 
        {
            $integration = $user->integrations()->where('siteID', $siteID)->first();
            
            $integration->items()->update([
                'revised' => false
            ]);
            
            foreach ((array)$products as $product) 
            {
                $marketPlaceItem = $integration->items()->where('marketPlaceItemID', $product['listing-id'])->first();
                               
                if (isset($marketPlaceItem)) 
                {
                    $marketPlaceItem->update([
                        'sku' => $product['seller-sku'],
                        'price' => $product['price'],
                        'quantityAvailable' => $product['quantity'],
                        'revised' => true
                    ]);

                    $spierItem = SPierItem::find($marketPlaceItem->spier_item_id);
                    $qty = $product['quantity'];
                    
                    if ($spierItem->quantityAvailable != $qty) 
                    {
                        $spierItem->update(['quantityAvailable' => $qty]); // very important to revise
                    }
                }
                else
                {
                    $spierItem = SPierItem::where('sku', $product['seller-sku'])->first();

                    if (!isset($spierItem)) 
                    {
                        $spierItem = new SPierItem;
                        $spierItem->sku = $product['seller-sku'];            
                
                        switch ($product['product-id-type']) 
                        {
                            case 3:
                                $spierItem->upc = $product['product-id'];
                                break;

                            case 4:
                                $spierItem->ean = $product['product-id'];
                                break;
                        }

                        $trans = [
                            "1" => "Used; Like New",
                            "2" => "Used; Very Good",
                            "3" => "Used; Good",
                            "4" => "Used; Acceptable",
                            "5" => "Collectible; Like New",
                            "6" => "Collectible; Very Good",
                            "7" => "Collectible; Good",
                            "8" => "Collectible; Acceptable",
                            "9" => "Not used",
                            "10" => "Refurbished",
                            "11" => "New"
                        ];

                        $condition = strtr($product['item-condition'], $trans);

                        $spierItem->title = utf8_encode(substr($product['item-name'], 0, 191));
                        $spierItem->description = utf8_encode($product['item-description']);
                        $spierItem->condition = $condition;
                        $spierItem->conditionID = $product['item-condition'];
                        $spierItem->quantityAvailable = $product['quantity'] ?: 0;

                        $saving = $user->spier_items()->save($spierItem);
                    }
                    
                    $spierItem->marketPlaceItems()->create([
                        'marketPlaceItemID' =>$product['listing-id'],
                        'asin' => $product['asin1'],
                        'integration_id' => $integration->id,
                        'sku' => $product['seller-sku'],
                        'price' => $product['price'],
                        'quantityAvailable' => $product['quantity'],
                        'imageUrl' => $product['image-url']
                    ]);
                }
            }

            $integration->items()->unrevised()->delete();
            $user->spier_items->each->updateSync();
        }
        else
        {
            return 'Oops! something went wrong with amazon mws.';
        }
	}
}