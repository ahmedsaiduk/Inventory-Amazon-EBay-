<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class SyncEbayOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ebay:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'syncing all users orders from ebay';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
    }
}
