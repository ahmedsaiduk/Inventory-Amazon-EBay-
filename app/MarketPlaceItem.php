<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MarketPlaceItem extends Model
{
    //
	protected $guarded = [''];

    public function scopeUnrevised($query)
    {
        return $query->where('revised', false);
    }

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }
    
    public function spier_item()
    {
        return $this->belongsTo(SPierItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'marketPlace_item_id');
    }

    public function strategy()
    {
        return $this->belongsTo(Strategy::class);
    }

    public function priceLogs()
    {
        return $this->hasMany(PriceLog::class, 'marketPlace_item_id');
    }

}
