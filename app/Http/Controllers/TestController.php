<?php

namespace App\Http\Controllers;

use Auth;
use App\Ebay;
use App\User;
use App\Test;
use App\Order;
use App\Amazon;
use App\SPierItem;
use Carbon\Carbon;
use App\Integration;
use App\MarketPlaceItem;
use Illuminate\Http\Request;
use App\Jobs\TestJob;
use App\Jobs\SyncEbayOrders;
use App\Jobs\SyncAmazonOrders;
use App\Jobs\SyncAllUsersOrders;
use App\Jobs\SyncAllUsersStores;
use App\DataTables\IntegrationsDataTable;

class TestController extends Controller
{
	use	EbayTraits\SyncEbayItems,
	 	EbayTraits\SyncEbayOrders, 
	 	AmazonTraits\SyncAmazonItems,
	 	AmazonTraits\SyncAmazonOrders,
		EbayTraits\SyncEbayCategories;

	// public function index(IntegrationsDataTable $dataTable)
 	public function index()
	{
		// $spier = SPierItem::find(1);
		// return $spier;
		// $user = Auth::user();
		// return $user->integrations->load('items');
		// $job = (new SyncAllUsersOrders())->onQueue('sellerpier');
            // dispatch($job);
           // $job = (new SyncAllUsersStores())->onQueue('sellerpier');
            // dispatch($job);
		// $users = User::subscribed()->get();

		// return $users->each->refreshOrders();

		// return $countries = Auth::user()->integrations()->orderBy('orders_count', 'desc')->take(3)->get();

		// $top5 =  Auth::user()->top5();
		// return $top5->pluck('transactions_count');		
		// return view('test');
		// $items->each->updateSync();
		// $order = Auth::user()->orders()->first();
		// return $order->purchaseDate->diffForHumans();
		// return Auth::user()->orders()->thisMonth()->get();
		// return Auth::user()->monthlyRevenue();
		// return Auth::user()->orders;
		
		// return $c = new Carbon('2017-06-01T01:23:27Z');

		// return \DB::table('users')->toSql();
		// return Auth::user()->spier_items()->unlinked()->get();

		// return Auth::user()->spier_items()->selectRaw('sku, title')->orderBy('transactions_count', 'desc')->take(5)->get();
		// return Auth::user()->integrations;
		// return Auth::user()->spier_items()->unlinked()->get();
		// $item = Auth::user()->spier_items()->with('marketPlaceItems')->first();
		// return $item->listedOn();
		// return Auth::user()->spier_items()->with('market_place_items_count')->where('market_place_items_count', 1)->get();

		// Test::where('status', false)->delete();
		// Test::query()->update(['title' => 'new title']);
		// return Test::all();

		// $test = factory(Test::class, 10)->create();
		// return $test;

		// return SPierItem::linked()->count();

		// return MarketPlaceItem::select('spier_item_id')->groupBy('spier_item_id')
															// ->havingRaw('COUNT(spier_item_id) > 1')
															// ->first();

		// return MarketPlaceItem::where('spier_item_id', '1588')->get();
		
		// print_r(Amazon::getAllItems(Auth::user(), 'ATVPDKIKX0DER'));
		
		// return $this->SyncEbayItems(Auth::user(), '0');
		// print_r(Amazon::getAllItems(Auth::user(), 'A1AM78C64UM0Y8'));
		// return $this->SyncAmazonItems(Auth::user(), 'ATVPDKIKX0DER');

		// $this->SyncAmazonOrders(Auth::user(), 'ATVPDKIKX0DER', true);
		// dispatch(new SyncAmazonOrders(Auth::user(), 'ATVPDKIKX0DER'));
		// dispatch(new SyncAmazonOrders(Auth::user(), 'A2EUQ1WTGCTBG2', true));
		// return back();

		// return Amazon::getOrderItems(Auth::user(), 'ATVPDKIKX0DER', '114-7043501-1516229');

		// return Auth::user()->integrations()->where('site', 'Ebay-us')->with('items')->first();
		// return Ebay::getSales(Auth::user(), '0', true);

		// return $this->SyncEbayOrders(Auth::user(), '0', true);

		// return Auth::user()->integrations->load('items');
		// $spierItem = SPierItem::where('sku','010N119804')->first();
		// return $spierItem->integrations();
		// return $this->SyncEbayOrders(Auth::user(),)


		// $integrations = Auth::user()->integrations()->where('marketPlace','Ebay')->get();
		
		// foreach ($integrations as $integration) 
		// {
		// 	$integs [] = substr($integration->site,5);	
		// }
		// return $integs;


        // $users = User::all(); // subscribed and integrated to eBay only 
        // $integrations = Integration::where('marketPlace','Ebay')->get();
        // return $integrations[0]->user;

		// $integration = Integration::find(2);
		// return $integration->items();

		// $spierItem = SPierItem::find(1);
		// return $spierItem->listedOn();

		// return Amazon::getSiteID('Amazon-de');
		// return Ebay::getSales(Auth::user(), 'EBAY-US');
		// return $this->Welcome();
		// $this->SyncEbayCategories(Auth::user());
		// return 'done';

		// return $_ENV['EBAY_SANDBOX_DEV_ID'];
		// return $userToken =Auth::user()->token->token;
		// return Auth::user()->load('bulkFiles')->load('token');
		// $userToken = Auth::user()->token->ebay;
		// return $userToken;

		// return \App\Ebay::getSuggestedCats('camera');
		// return \App\Ebay::getCategorySpecs('31388');
		// return \App\Ebay::getSpecsFile('6111858207','6237739897');

		// return env('EBAY_PRODUCTION_AUTH_TOKEN');
		// return App\Http\Controllers\StoreCategoryController::clean_categories();
		// return view('test');
		// return \Redirect::to('https://google.com');
		// return \Hash::make('yamen',[ 'rounds' => 	15 ]);
		// return Auth::user()->integrations->ebaySessionId;
		// return App\Ebay::getActiveItems(Auth::user());
		
		// orders
		// $response = App\Ebay::getSales(Auth::user());
		// return $response->OrderArray->Order[0]->TransactionArray->Transaction[0];

		//caregories
		// return App\Ebay::getStore(Auth::user()); // no custom categories !!!


		//sellerlist
		// return Ebay::getActiveItems(Auth::user(), '0');

		//ebayselling
	}
}
