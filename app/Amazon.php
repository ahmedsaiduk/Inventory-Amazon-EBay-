<?php

namespace App;

use \Zlegend\MWSClient;
use \Zlegend\MWSProduct;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Amazon extends Model
{
    //
    protected $table = 'amazon_items';
    protected $guarded = [''];
    
    private static $client;

    static function init($user, $siteID)
    {
        $amazon = $user->integrations()->where('siteID', $siteID)->first();

	    self::$client = new MWSClient([
            'Marketplace_Id' => $siteID,
            'Access_Key_ID' => env('Access_Key_ID'),
            'Secret_Access_Key' => env('Secret_Access_Key'),
            'Seller_Id' => $amazon->sellerID,
            'MWSAuthToken' => $amazon->authToken 
        ]);
    }

	static function getAllItems($user, $siteID)
	{
        self::init($user, $siteID);

        $reportId = self::$client->RequestReport('_GET_MERCHANT_LISTINGS_ALL_DATA_');
        sleep(40);
        $products = self::$client->GetReport($reportId);

        if ($products) 
        {
            return $products;
        }
        else
        {
            return false;
        }
    }

    // static function getSites($user)
    // {
    //     self::init($user);

    //     $sites = self::$client->ListMarketplaceParticipations();
    // }

    static function getSiteID($site)
    {   
        switch ($site) 
        {
            case 'Amazon-us':
            case 'Amazon-ca':
            case 'Amazon-mx':
            case 'Amazon-br':
            case 'Amazon-de':
            case 'Amazon-es':
            case 'Amazon-fr':
            case 'Amazon-it':
            case 'Amazon-uk':
            case 'Amazon-in':
            case 'Amazon-jp':
            case 'Amazon-cn': return env($site);break;
        }
    }

    static function getTimezon($site)
    {
        # code...
    }

    static function getCountry($site)
    {
        switch ($site) 
        {
            case 'Amazon-us':   return 'USA';
            case 'Amazon-ca':   return 'CAN';
            case 'Amazon-mx':   return 'MEX'; 
            case 'Amazon-br':   return 'BRA';
            case 'Amazon-de':   return 'DEU';
            case 'Amazon-es':   return 'ESP';
            case 'Amazon-fr':   return 'FRA';
            case 'Amazon-it':   return 'ITA';
            case 'Amazon-uk':   return 'GBR';
            case 'Amazon-in':   return 'IND';
            case 'Amazon-jp':   return 'JPN';
            case 'Amazon-cn':   return 'CHN';
        }
    }

    static function getSiteCurrency($siteID)        
    {
        switch ($siteID) 
        {
            case env('Amazon-us'):
                return 'USD';
                break;
            case env('Amazon-ca'):
                return 'CAD';
                break;
            case env('Amazon-mx'):
                return 'MXN';
                break;
            case env('Amazon-de'):
                return 'EUR';
                break;
            case env('Amazon-es'):
                return 'EUR';
                break;
            case env('Amazon-it'):
                return 'EUR';
                break;
            case env('Amazon-fr'):
                return 'EUR';
                break;
            case env('Amazon-uk'):
                return 'GBR';
                break;
            case env('Amazon-in'):
                return 'INR';
                break;
                // Rest of them
        }
    }

    static function getSales($user, $siteID, $initial = false, $startDate = null)
    {
        self::init($user, $siteID);

        $timeFrom = Carbon::now();
        
        if ($initial) 
        {
            $timeFrom->startOfMonth();
        }
        else if(isset($startDate))
        {
            $timeFrom = $startDate;
        }
        else
        {
            $timeFrom->subMinutes(60);
        }

        return $orders = self::$client->ListOrders($timeFrom);
    }

    static function getOrderItems($user, $siteID, $orderID)
    {
        self::init($user, $siteID);

        return $items = self::$client->ListOrderItems($orderID);
    }

    static function checkCredentials($siteID, $sellerID, $authToken)
    {
        self::$client = new MWSClient([
            'Marketplace_Id' => $siteID,
            'Access_Key_ID' => env('Access_Key_ID'),
            'Secret_Access_Key' => env('Secret_Access_Key'),
            'Seller_Id' => $sellerID,
            'MWSAuthToken' => $authToken 
        ]);

        if (self::$client->validateCredentials()) {
            return true;
        } else {
            return false;
        }
    }

    static function updateQuantity($user, $siteID, $sku, $qty)
    {
        self::init($user, $siteID);

        self::$client->updateStock([
            $sku => $qty
        ]);
    }

    static function updatePrice($user, $siteID, $sku, $price)
    {
        self::init($user, $siteID);

        self::$client->updatePrice([
            $sku => $price
        ]);
    }

    public function postProduct(Request $request)
	{
        $product = new MWSProduct();
        $product->sku = $request->input('sku');
        $product->price = $request->input('price');
        $product->product_id = $request->input('product_id');
        $product->product_id_type = $request->input('product_id_type');
        $product->condition_type = $request->input('condition_type'); 
        $product->condition_note = $request->input('condition_note');
        $product->quantity = $request->input('quantity');
        $product->conditionNote = $request->input('conditionNote'); // if not new


        if ($product->validate()) 
        {
            // You can also submit an array of MWSProduct objects
            return $result = $this->client->postProduct($product);
    

        } 
        else 
        {
            return $errors = $product->getValidationErrors();        
        } 

	}

	public function updateProduct(Request $request)
	{
        $sku = $request->input('sku');
        $price = $request->input('price');
        $qty = $request->input('qty');

        $result = $this->client->updateStock([
            $sku => $qty,
        ]);

        // $info = $this->client->GetFeedSubmissionResult($result['FeedSubmissionId']);


        $resultp = $this->client->updatePrice([
            $sku => $price,
        ]);
        
        return $result;

        // $info = $this->client->GetFeedSubmissionResult($result['FeedSubmissionId']);

    }
}
