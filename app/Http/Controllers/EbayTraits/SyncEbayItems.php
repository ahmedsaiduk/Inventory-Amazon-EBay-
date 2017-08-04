<?php 

namespace App\Http\Controllers\EbayTraits;

use App\Ebay;
use App\SPierItem;

trait SyncEbayItems
{
	protected function SyncEbayItems($user, $siteID)
	{
		$activeItems = Ebay::getActiveItems($user, $siteID);

        $integration = $user->integrations()->where('siteID', $siteID)->first();
        
        $integration->items()->update([
            'revised' => false
        ]);
        
        foreach ($activeItems as $activeItem) 
        {
            $marketPlaceItem = $integration->items()->where('marketPlaceItemID', $activeItem->ItemID)->first();
                                      
            if (isset($marketPlaceItem)) 
            {
                $marketPlaceItem->update([
                    'sku' => $activeItem->SKU ?: null,
                    'price' => $activeItem->SellingStatus->CurrentPrice->value,
                    'quantityAvailable' => $activeItem->Quantity - $activeItem->SellingStatus->QuantitySold,
                    'quantitySold' => $activeItem->SellingStatus->QuantitySold,
                    'revised' => true
                ]);

                $spierItem = $user->spier_items()->find($marketPlaceItem->spier_item_id);
                $qty = $activeItem->Quantity - $activeItem->SellingStatus->QuantitySold;
                
                if ($spierItem->quantityAvailable != $qty) 
                {
                    $spierItem->update(['quantityAvailable' => $qty]); // very important to revise
                }
            }
            else 
            {
                $spierItem = $user->spier_items()->where('sku', $activeItem->SKU)->first();
                
                if (!isset($spierItem)) 
                {
                    $spierItem = new SPierItem;
                    $spierItem->sku = $activeItem->SKU ?: null;            
                    // $spierItem->upc = $activeItem->ProductListingDetails->UPC ?: 'empty'; // not returned with this call
                    $spierItem->title = $activeItem->Title;
                    $spierItem->description = $activeItem->Description ?: null;
                    $spierItem->condition = $activeItem->ConditionDisplayName;
                    $spierItem->conditionDescription = $activeItem->ConditionDescription ?: null;
                    $spierItem->quantityAvailable = $activeItem->Quantity - ($activeItem->SellingStatus->QuantitySold ?: 0);
                    $spierItem->quantitySold = $activeItem->SellingStatus->QuantitySold ?: 0;
                
                    $saving = $user->spier_items()->save($spierItem);   
                }

                $spierItem->marketPlaceItems()->create([
                    'marketPlaceItemID' => $activeItem->ItemID,
                    'integration_id' => $integration->id,
                    'upc' => $spierItem->upc,
                    'ean' => $spierItem->ean,
                    'sku' => $spierItem->sku,
                    'price' => $activeItem->SellingStatus->CurrentPrice->value,
                    'quantityAvailable' => $activeItem->Quantity - $activeItem->SellingStatus->QuantitySold,
                    'quantitySold' => $activeItem->SellingStatus->QuantitySold,
                    // 'imageURL' => $activeItem->PictureDetails->PictureURL ?: null,
                    'status' => $activeItem->SellingStatus->ListingStatus
                ]);
            }
        }

        $integration->items()->unrevised()->delete();
        $user->spier_items->each->updateSync();
	}

            // $spierItem->postalCode = $activeItem->PostalCode ?: null;
            // $spierItem->country = $activeItem->Country ?: null;
            // $spierItem->shippingType = $activeItem->ShippingDetails->ShippingType;
            // $spierItem->shippingService = $activeItem->ShippingDetails->ShippingServiceOptions[0]->ShippingService;
            // $spierItem->paymentMethods = implode(" ", $activeItem->PaymentMethods);
            // $spierItem->paymentMethods = $activeItem->PaymentMethods[0];        
            // $spierItem->paypalEmail = $activeItem->PayPalEmailAddress;
            // $spierItem->dispatchTimeMax = $activeItem->DispatchTimeMax;

            // category
            // $category = StoreCategory::where('ebay_category_id', $activeItem->Storefront->StoreCategoryID)->first();
            // $spierItem->store_category_id = $category->id;

            // if ($activeItem->ReturnPolicy->ReturnsAccepted == 'Returns Accepted') 
            // {
            //     $spierItem->returnAccepted = true;
            //     $spierItem->refund = $activeItem->ReturnPolicy->Refund;
            //     $spierItem->returnWithin = $activeItem->ReturnPolicy->ReturnsWithin;
            //     $spierItem->shippingCostPaidBy = $activeItem->ReturnPolicy->ShippingCostPaidBy;
            // }
            // else 
            // {
            //     $spierItem->returnAccepted = false;
            // }
}