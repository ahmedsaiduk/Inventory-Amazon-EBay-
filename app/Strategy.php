<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Strategy extends Model
{
    protected $guarded =[];

    public function integration()
    {
    	return $this->belongsTo(Integration::class);
    }

    public function items()
    {
    	return $this->hasMany(MarketPlaceItem::class);
    }

    public function priceLogs()
    {
    	return $this->hasMany(PriceLog::class);
    }
}
