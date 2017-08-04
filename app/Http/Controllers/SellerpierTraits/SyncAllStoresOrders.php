<?php

namespace App\Http\Controllers\SellerpierTraits;

use App\User;

trait SyncAllStoresOrders
{
	protected function syncAllStoresOrders() // every 30 mins
	{
		$users = User::subscribed()->get();

		$users->each->refreshOrders();	
	}
}