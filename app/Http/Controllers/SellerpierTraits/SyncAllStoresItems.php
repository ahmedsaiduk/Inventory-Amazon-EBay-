<?php

namespace App\Http\Controllers\SellerpierTraits;

use App\User;

trait SyncAllStoresItems
{
	protected function syncAllStoresItems() // daily
	{
		$users = User::subscribed()->get();

		$users->each->refreshStores();	
	}
}