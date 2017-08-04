<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [''];

    public function scopeAwaitingPayment($query)
    {
        return $query->where('status', 'awaiting payment');
    }

    public function scopeAwaitingShipment($query)
    {
        return $query->where('status', 'awaiting shipment');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    } 

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    } 

    public function scopeThisMonth($query)
    {
        return $query->where('purchaseDate', '>=', Carbon::now()->startOfMonth())
                     ->where('status', 'shipped');
    }

    public function scopeThisYear($query)
    {
        return $query->where('purchaseDate', '>=', Carbon::now()->startOfYear())
                     ->where('status', 'shipped');
    }

    public function scopeLastFiveDays($query)
     {
         return $query->where('purchaseDate', '>=', Carbon::now()->subDays(5));
     } 

    public function transactions()
    {
    	return $this->hasMany(Transaction::class);
    }

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }

    public function updateTotals()
    {
    	if ($this->integration->marketPlace == 'Amazon') // Amazon: shipping, tax, gift
        {
            $this->update([
                'totalShipping' => $this->transactions->sum('shippingCost'),
                'totalTax' => $this->transactions->sum('tax'),
                'totalGift' => $this->transactions->sum('gift')
            ]); 
        }
        elseif ($this->integration->marketPlace == 'Ebay') // Ebay: tax, not sure about shipping
        {
            $this->update([
                // 'totalShipping' => $this->transactions->sum('shippingCost'),
                'totalTax' => $this->transactions->sum('tax')
            ]);
        }
    }

    public function getPackingSlipURL()
    {
        $url = '/orders';

        if ($this->integration->marketPlace == 'Amazon') 
        {
            $url = $this->integration->packingSlipURL.$this->siteOrderId;        
        }

        elseif($this->integration->marketPlace == 'Ebay')
        {
            $trans = $this->transactions()->first();
            $marketItem = $trans->marketPlaceItem;

            if (isset($marketItem)) 
            {
                $url = substr_replace($this->integration->packingSlipURL, $marketItem->marketPlaceItemID, 63, 0);
                $url = substr_replace($url, $trans->transactionID, 90, 0);
            }
        }

        return $url;
    }
}
