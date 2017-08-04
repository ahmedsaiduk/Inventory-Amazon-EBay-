<?php

namespace App\Http\Controllers;

use Auth;
use App\Ebay;
use App\SPierItem;
use Carbon\Carbon;
use App\SpecCategory;
use App\StoreCategory;
use Illuminate\Http\Request;
use App\Jobs\SyncEbayStore;
use App\Jobs\SyncEbayOrders;
use App\Jobs\SyncAmazonItems;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    use AmazonTraits\SyncAmazonItems;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $top5 = Auth::user()->top5();
        $items = Auth::user()->spier_items_count;
        $orders = Auth::user()->orders_count;
        $monthlyRevenue = Auth::user()->monthlyRevenue();
        $countries = Auth::user()->integrations()->orderBy('orders_count', 'desc')->take(3)->get();

        $revenue = Auth::user()->orders()->thisYear()
                                        ->selectRaw('DATE_FORMAT(purchaseDate, "%m") as month, sum(totalPrice) as revenue')
                                        ->groupBy('month')
                                        ->pluck('revenue', 'month');

        $revenueByDay = Auth::user()->orders()->thisYear()
                                        ->selectRaw('DATE_FORMAT(purchaseDate, "%d/%m") as day, sum(totalPrice) as revenue')
                                        ->groupBy('day')
                                        ->pluck('revenue', 'day');

        $unshipped = Auth::user()->orders()
                                ->awaitingShipment()
                                ->lastFiveDays()
                                ->selectRaw('DATE_FORMAT(purchaseDate, "%d") as day, count(*) as orders')
                                ->groupBy('day')
                                ->pluck('orders','day');

        return view('home', compact('top5', 'items', 'orders', 'monthlyRevenue', 'countries', 'revenue', 'unshipped', 'revenueByDay'));
    }
}