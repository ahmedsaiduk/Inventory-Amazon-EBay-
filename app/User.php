<?php

namespace App;

use App\Http\Controllers\EbayTraits;
use App\Http\Controllers\AmazonTraits;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Http\Controllers\SellerpierTraits\CleanSpierItemsTrait;

class User extends Authenticatable
{
    use Notifiable,
        CleanSpierItemsTrait,
        EbayTraits\SyncEbayItems,
        EbayTraits\SyncEbayOrders,
        AmazonTraits\SyncAmazonItems,
        AmazonTraits\SyncAmazonOrders; 

    protected $fillable = [
        'name', 'email', 'password', 'subscribed'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('spierItemsCount', function ($builder)
        {
            $builder->withCount('spier_items');
        });

        static::addGlobalScope('integrationsCount', function ($builder)
        {
            $builder->withCount('integrations');
        });

        static::addGlobalScope('ordersCount', function ($builder)
        {
            $builder->withCount('orders'); 
        });
    }

    public function scopeSubscribed($query)
    {
        return $query->where('subscribed', true);
    }

    public function scopeUnsubscribed($query)
    {
        return $query->where('subscribed', false);
    }

    public function setting()
    {
        return $this->hasOne(Setting::class);
    }

    public function spier_items()
    {
        return $this->hasMany(SPierItem::class);
    }

    public function bulkFiles()
    {
        return $this->hasMany(BulkFile::class);
    }

    public function integrations()
    {
        return $this->hasMany(Integration::class);
    }

    public function store_categories()
    {
        return $this->hasMany(StoreCategory::class);
    }

    public function orders()
    {
        return $this->hasManyThrough(Order::class, Integration::class);
    }

    public function monthlyRevenue()
    {
        return $this->orders()->thisMonth()->shipped()->sum('totalPrice');
    }

    public function subscribe()
    {
        $this->update(['subscribed' => true]);
    }

    public function refreshStores() // daily
    {
        foreach ($this->integrations()->enabled()->get() as $store) 
        {
            switch ($store->marketPlace) 
            {
                case 'Amazon':
                    $this->SyncAmazonItems($this, $store->siteID);
                    break;

                case 'Ebay':
                    $this->SyncEbayItems($this, $store->siteID);
                    break;

                // rest of integrations
            }
        }

        $this->cleanSpierItems($this);
    }

    public function refreshOrders() // every 30 mins
    {
        foreach ($this->integrations()->enabled()->get() as $store) 
        {
            switch ($store->marketPlace) 
            {
                case 'Amazon':
                    $this->SyncAmazonOrders($this, $store->siteID);
                    break;

                case 'Ebay':
                    $this->SyncEbayOrders($this, $store->siteID);
                    break;

                // rest of integrations
            }   
        }
    }

    public function top5()
    {
        return $this->spier_items()->selectRaw('sku, title')->orderBy('transactions_count', 'desc')->take(5)->get();
    }
}
