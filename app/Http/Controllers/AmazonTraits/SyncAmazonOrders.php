<?php

namespace App\Http\Controllers\AmazonTraits;

use App\Amazon;
use Carbon\Carbon;

trait SyncAmazonOrders 
{
	protected function SyncAmazonOrders($user, $siteID, $initial = false, $startDate = null)
	{
        $integration = $user->integrations()->where('siteID', $siteID)->first();
		
		$orders = Amazon::getSales($user, $siteID, $initial, $startDate);

		if (!empty($orders)) 
		{
			$throttle = 1;

			if (!isset($orders[0]['OrderStatus'])) 
			{
				$temp = $orders;
				$orders = [];
				$orders [] = $temp;
			}

			foreach ($orders as $order) 
			{
				switch ($order['OrderStatus']) 
				{
					case 'Shipped':
						$status = 'shipped';
						break;
					case 'Unshipped':
						$status = 'awaiting shipment';
						break;
					case 'Pending':
						$status = 'awaiting payment';
						break;
					default:
						$status = 'pending';
						break;
				}

				$purchaseDate = Carbon::parse($order['PurchaseDate']);

				$savedOrder = $integration->orders()->updateOrCreate(['siteOrderId' => $order['AmazonOrderId']],[
					'status' => $status,
					'totalPrice' => isset($order['OrderTotal']) ? (double) $order['OrderTotal']['Amount']: 00.00,
					'purchaseDate' => $purchaseDate,
					'shippingService' => isset($order['ShipmentServiceLevelCategory']) ? $order['ShipmentServiceLevelCategory'] : null,
					'buyerName' => isset($order['ShippingAddress']) ? $order['ShippingAddress']['Name']: null
				]);

				if ($throttle % 30 == 0) 
				{
					sleep(15);
				}

				$items = Amazon::getOrderItems($user, $siteID, $order['AmazonOrderId']);

				if (!empty($items))
				{
					foreach ($items as $item) 
					{
						$marketPlaceItem = $integration->items()->where('sku', $item['SellerSKU'])
																->orWhere('asin', $item['ASIN'])
																->first();
						if (isset($marketPlaceItem)) 
						{
							$savingTransaction = $savedOrder->transactions()
													   ->updateOrCreate(['transactionID' => $item['OrderItemId']],[
													   		'marketPlace_item_id' => $marketPlaceItem->id,
													   		'quantity' => (integer)$item['QuantityOrdered'],
													   		'price' => isset($item['ItemPrice']) ? (double)$item['ItemPrice']['Amount'] : 00.00,
													   		'shippingCost' => isset($item['ShippingPrice']) ? (double)$item['ShippingPrice']['Amount'] : 00.00,
													   		'tax' => isset($item['ItemTax']) ? (double)$item['ItemTax']['Amount'] : 00.00,
													   		'gift' => isset($item['GiftWrapPrice']) ? (double)$item['GiftWrapPrice']['Amount'] : 00.00
														]);
							
							if ($marketPlaceItem->spier_item->quantityAvailable != $marketPlaceItem->quantityAvailable) 
	                        {
	                            $marketPlaceItem->spier_item()->update([
	                                'quantityAvailable' => $marketPlaceItem->quantityAvailable
	                            ]);
	                        }
						}
					}
				}

				$savedOrder->updateTotals();

				$throttle ++;
			}
        }
	}
}