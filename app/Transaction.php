<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $guarded = [''];

    public function order()
    {
    	return $this->belongsTo(Order::class);
    }

    public function marketPlaceItem()
    {
    	return $this->belongsTo(MarketPlaceItem::class);
    }
}
