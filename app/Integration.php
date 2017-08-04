<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    //
    protected $guarded = [''];
    protected $hidden = ['sellerID','authToken'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('itemsCount', function ($builder)
        {
            $builder->withCount('items');
        });

        static::addGlobalScope('ordersCount', function ($builder)
        {
            $builder->withCount('orders');
        });
    }

    public function scopeEnabled($query)
    {
        return $query->where('enabled', true);
    }

    public function scopeSynced($query)
    {
        return $query->where('qtySync', true);
    }
    
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function items()
    {
    	return $this->hasMany(MarketPlaceItem::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function strategies()
    {
        return $this->hasMany(Strategy::class);
    }

    public function currency() // not important
    {
        switch ($this->currency) 
        {
            case 'USD':
                return '$';
                break;
        }
    }
}
