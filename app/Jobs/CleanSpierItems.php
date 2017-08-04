<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Http\Controllers\SellerpierTraits\CleanSpierItemsTrait;

class CleanSpierItems implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, CleanSpierItemsTrait;

    protected $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->cleanSpierItems($this->user);
    }
}
