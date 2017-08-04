<?php

namespace App;

use App\Events\SPierItemUpdated;
use Illuminate\Database\Eloquent\Model;

class SPierItem extends Model
{
    //
    protected $table = 'spier_items';
    protected $guarded = [''];
    protected $events = [
        'updated' => SPierItemUpdated::class
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('marketPlaceItemsCount', function ($builder)
        {
            $builder->withCount('marketPlaceItems');
        });

        static::addGlobalScope('transactionsCount', function ($builder)
        {
            $builder->withCount('transactions');
        });
    }

    public function scopeUnlinked($query)
    {
        return $query->whereDoesntHave('marketPlaceItems');
    }

    public function scopeLinked($query)
    {
        return $query->whereHas('marketPlaceItems');
    }

    public function scopeSynced($query)
    {
        return $query->where('synced', true);
    }

    public function scopeUnsynced($query)
    {
        return $query->where('synced', false);
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function store_category()
    {
        return $this->belongsTo(StoreCategory::class);
    }

    public function attributes()
    {
        return $this->hasMany(Attribute::class,'spier_item_id');
    }

    public function marketPlaceItems()
    {
        return $this->hasMany(MarketPlaceItem::class,'spier_item_id');
    }

    public function transactions()
    {
        return $this->hasManyThrough(Transaction::class, MarketPlaceItem::class, 'spier_item_id', 'marketPlace_item_id');
    }

    public function listedOn()
    {
        $listedOn = [];

        foreach ($this->marketPlaceItems as $marketPlaceItem) 
        {
            $listedOn [] = $marketPlaceItem->integration->site;
        }

        return $listedOn;
    }

    public function updateSync()
    {
        $synced = true;
        
        foreach ($this->marketPlaceItems as $item) 
        {
            if ($this->quantityAvailable != $item->quantityAvailable) 
            {
                $synced = false;
            }
        }

        if (!$synced) 
        {
            $this->update(['synced' => $synced]);
        }
    }
}