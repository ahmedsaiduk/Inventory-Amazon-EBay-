<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Jobs\SyncEbayStore;

Route::get('/', function () {
	// if (Auth::guest()) {
	    return view('welcome');
	// }
	// else{
		// return \Redirect::to('home')->with([
		// 		'color' => 'green',
		// 		'message' => 'yes'
		// 	]);
	// }
});

Auth::routes();
Route::group(['middleware' => ['web','auth']], function (){    
	
	Route::get('home', 'HomeController@index');
	Route::resource('items', 'SPierItemController');
	Route::resource('orders','OrderController', ['only' => ['index']]);
	Route::resource('categories','StoreCategoryController');
	Route::resource('integrations', 'IntegrationController');
	Route::get('integrations/create/ebay','IntegrationController@ebay');
	Route::get('integrations/ebay/approval','IntegrationController@ebayToken');
	Route::get('integrations/refresh/{integration}', 'IntegrationController@refresh');
	// Route::get('refreshall', 'IntegrationController@refreshall'); // overuse
	Route::get('bulk','SPierItemController@getFile');
	Route::post('bulk', 'SPierItemController@getFile');
	Route::post('specs/download','SPierItemController@downloadSpecs');

});

// Route::get('test','TestController@index');
Route::resource('test','TestController');


Route::get('upc/{pid}','SPierItemController@getProduct'); // catalog
Route::get('add','SPierItemController@testAdd'); // test add
Route::get('details','SPierItemController@ebayDetails');

Route::get('storage', function (){
	// return \Excel::load('storage/app/uploads/bulks/1486473807_yamen.xls')->get();
	// return \Auth::user()->bulkFiles[0]->file;

	// return \Excel::load('storage/app/uploads/bulks/'.\Auth::user()->bulkFiles[1]->file)->get();

	// unlink(storage_path('app/uploads/bulks/1486633441_yamen.xls'));

	// return Auth::user()->load('Token')->;
});

Route::get('excel',function (){
	\Excel::create('ebay-items', function($excel) {
	    $excel->sheet('ebay', function($sheet) {
	        // $sheet->fromArray(array(
	        //     array('data1', 'data2'),
	        //     array('data3', 'data4')
	        // ));
	        $sheet->fromModel(\App\Ebay::all());
	    });
	})->export('xls');

	// return \Excel::load(storage_path('app/templates/').'InventoryTemplate.xlsx')->get();
});

