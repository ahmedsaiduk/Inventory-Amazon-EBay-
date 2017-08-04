<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Excel;

class BulkFile extends Model
{
    protected $fillable = ['file','user_id'];
     
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

}
