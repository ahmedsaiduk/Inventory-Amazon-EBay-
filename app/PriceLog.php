<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceLog extends Model
{
    //
    protected $guarded = [];

    public function item()
    {
    	return $this->belongsTo(MarketPlaceItem::class);
    }

    public function strategy()
    {
    	return $this->belongsTo(Strategy::class);
    }

}
