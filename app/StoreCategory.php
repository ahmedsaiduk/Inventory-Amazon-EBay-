<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StoreCategory extends Model
{
    //
    protected $fillable = ['name','ebay_category_id','parent_category_id','user_id','order'];
    
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function spier_items()
    {
    	return $this->hasMany(SPierItem::class);
    }

    public function parent_category()
    {
        return $this->belongsTo(StoreCategory::class,'parent_category_id');
    }

    public function sub_categories()
    {
        return $this->hasMany(StoreCategory::class,'parent_category_id');
    }

    // relation to marketPlaceItem and Integration
}
