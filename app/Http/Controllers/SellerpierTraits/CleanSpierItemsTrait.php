<?php

namespace App\Http\Controllers\SellerpierTraits;

use App\SPierItem;

trait CleanSpierItemsTrait 
{
	public function cleanSpierItems($user)
    {
    	$user->spier_items()->unlinked()->delete();
    }
}