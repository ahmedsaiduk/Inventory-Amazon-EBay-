<?php

namespace App\Http\Controllers\EbayTraits;

use App\Ebay;
use App\Transaction;
use App\MarketPlaceItem;

trait SyncEbayOrders
{
	protected function SyncEbayOrders($user, $siteID, $initial = false, $startDate = null)
	{
        $integration = $user->integrations()->where('siteID', $siteID)->first();

		$orders = Ebay::getSales($user, $siteID, $initial, $startDate);

        if (!empty($orders)) 
        {
            foreach ($orders as $order) 
            {
                switch ($order->OrderStatus) 
                {
                    case 'Completed':
                        $status = 'shipped';
                        break;
                    case 'Active':
                        $status = 'awaiting shipment';
                        break;
                    default:
                        $status = 'pending';
                        break;
                }

                $savedOrder = $integration->orders()->updateOrCreate(['siteOrderId' => $order->OrderID],[
                    'status' => $status,
                    'totalPrice' => $order->Total->value,
                    'purchaseDate' => $order->CreatedTime,
                    'shippingService' => $order->ShippingServiceSelected->ShippingService,
                    'totalShipping' => $order->ShippingServiceSelected->ShippingServiceCost->value,
                    'buyerName' => $order->ShippingAddress->Name
                ]);

                foreach ($order->TransactionArray->Transaction as $transaction) 
                {
                    $marketPlaceItem = $integration->items()->where('marketPlaceItemID', $transaction->Item->ItemID)
                                                            ->first();
                
                    if (isset($marketPlaceItem)) 
                    {
                        $savingTransaction = $savedOrder->transactions()
                                                        ->updateOrCreate(['transactionID' => $transaction->TransactionID],[
                                                            'marketPlace_item_id' => $marketPlaceItem->id,
                                                            'quantity' => $transaction->QuantityPurchased,
                                                            'price' => $transaction->TransactionPrice->value,
                                                            'shippingCost' => isset($transaction->ActualShippingCost->value) ?: 00.00,
                                                            'tax' => isset($transaction->Taxes->TotalTaxAmount->value) ?: 00.00,
                                                            'trackingNumber' => $transaction->ShippingDetails->ShipmentTrackingDetails[0] ? $transaction->ShippingDetails->ShipmentTrackingDetails[0]->ShipmentTrackingNumber : null
                                                        ]);

                        if ($marketPlaceItem->spier_item->quantityAvailable != $marketPlaceItem->quantityAvailable) 
                        {
                            $marketPlaceItem->spier_item()->update([
                                'quantityAvailable' => $marketPlaceItem->quantityAvailable
                            ]);
                        }
                    }
                }
                
                $savedOrder->updateTotals();
            }
        }

	}
}