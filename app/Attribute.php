<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    //
    protected $guarded = [''];
    public $timestamps = false;
    
    public function spierItem()
    {
    	return $this->belongsTo(SPierItem::class);
    }
}
