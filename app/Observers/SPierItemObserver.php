<?php

namespace App\Observers;

use App\SPierItem;
use App\Jobs\UpdateItemQty;
use Illuminate\Foundation\Bus\DispatchesJobs;

class SPierItemObserver	
{
	use DispatchesJobs;

	public function updated(SPierItem $spierItem)
	{
		$job = (new UpdateItemQty($spierItem))->onQueue('sellerpier'); // @param except later
		// dispatch($job);	
	}
}
