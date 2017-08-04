<?php

namespace App;

use Auth;
use Excel;
use Carbon\Carbon;
use \DTS\eBaySDK\Trading;
use \DTS\eBaySDK\Product;
use \DTS\eBaySDK\Finding;
use \DTS\eBaySDK\Constants;
// use \DTS\eBaySDK\FileTransfer;
use Illuminate\Database\Eloquent\Model;

class Ebay extends Model
{
    protected $table = 'ebay_items';
    protected $guarded = [''];
    private static $file = [
        'action','site','store category id','spec category id','sku','upc','title','cost','quantity available','condition','condition description','description','vendor name'
        ];
    private static $config;
    private static $service;

    public function integration()
    {
        return $this->belongsTo(Integration::class);
    }
    
    public function spier_item()
    {
        return $this->belongsTo(SPierItem::class);
    }

    static function initConfig($user)
    {
        self::$config = [
                'sandbox' => [
                'credentials' => [
                    'devId' => env('EBAY_SANDBOX_DEV_ID'),
                    'appId' => env('EBAY_SANDBOX_APP_ID'),
                    'certId' => env('EBAY_SANDBOX_CERT_ID'),
                ],
                'authToken' => $user->token->ebayAuth
            ]
        ];
    }

    static function prodInitConfig($user, $siteID)
    {
        $ebay = $user->integrations()->where('siteID', $siteID)->first();
        
        if (isset($ebay)) 
        {
            $token = $ebay->authToken;
        }
        else
        {
            $token = null;
        }
        
        self::$config = [
                'production' => [
                'credentials' => [
                    'devId' => env('EBAY_PRODUCTION_DEV_ID'),
                    'appId' => env('EBAY_PRODUCTION_APP_ID'),
                    'certId' => env('EBAY_PRODUCTION_CERT_ID'),
                ],
                'authToken' => $token
            ]
        ];
    }

    static function initTrading()
    {
        self::$service = new Trading\Services\TradingService([
            'credentials' => self::$config['sandbox']['credentials'],
            'sandbox' => true,
            'siteId' => Constants\SiteIds::US
        ]);
    }
    
    static function prodInitTrading($siteID)
    {
        self::$service = new Trading\Services\TradingService([
            'credentials' => self::$config['production']['credentials'],
            'siteId' => $siteID
        ]);
    }

    static function prodInitProduct()
    {
        self::$service = new Product\Services\ProductService([
            'credentials' => self::$config['production']['credentials'],
            'siteId' => Constants\SiteIds::US
        ]);
    }

    static function prodInitFinding()
    {
        self::$service = new Finding\Services\FindingService([
            'credentials' => self::$config['production']['credentials'],
            'siteId' => Constants\SiteIds::US
        ]);   
    }

    public function getUser($user, $siteID)
    {
        self::prodInitConfig($user, $siteID);
        self::prodInitTrading($siteID);

        $request = new Trading\Types\GetUserRequestType();
        $request->RequesterCredentials = new Trading\Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = self::$config['production']['authToken'];

        $response = self::$service->getUser($request);

        if (isset($response->Errors)) 
        {
                foreach ($response->Errors as $error) 
                {
                    printf(
                        "%s: %s\n%s\n\n",
                        $$error->SeverityCode === Trading\Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                        $error->ShortMessage,
                        $error->LongMessage
                    );
                }
            }

        if ($response->Ack !== 'Failure') 
        {
            return $response;
        }

    }

    static function getActiveItems($user, $siteID)
    {
        self::prodInitConfig($user, $siteID);
        self::prodInitTrading($siteID);

        $request = new Trading\Types\GetMyeBaySellingRequestType();
        $request->RequesterCredentials = new Trading\Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = self::$config['production']['authToken'];
        
        $request->ActiveList = new Trading\Types\ItemListCustomizationType();
        $request->ActiveList->Include = true;
        
        $request->ActiveList->Pagination = new Trading\Types\PaginationType();
        $pageNumber = 1;

        do 
        {
            $request->ActiveList->Pagination->PageNumber = $pageNumber;
            $response = self::$service->getMyeBaySelling($request);

            if (isset($response->Errors)) {
                foreach ($response->Errors as $error) {
                    printf(
                        "%s: %s\n%s\n\n",
                        $$error->SeverityCode === Trading\Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                        $error->ShortMessage,
                        $error->LongMessage
                    );
                }
            }

            if ($response->Ack !== 'Failure' && isset($response->ActiveList)) 
            {
                foreach ($response->ActiveList->ItemArray->Item as $item) 
                {
                    $activeItems [] = $item;
                }
            }
     
            $pageNumber ++;
        }
        while (isset($response->ActiveList) && $pageNumber <= $response->ActiveList->PaginationResult->TotalNumberOfPages);

        return $activeItems;
    }

    static function getItem($user, $id, $siteID)
    {
        self::prodInitConfig($user, $siteID);
        self::prodInitTrading($siteID);

        $request = new Trading\Types\GetItemRequestType();

        $request->RequesterCredentials = new Trading\Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = self::$config['production']['authToken'];

        
        $request->ItemID = (string) $id;

        $response = self::$service->getItem($request);
        
        if (isset($response->Errors)) {
            foreach ($response->Errors as $error) {
                printf(
                    "%s: %s\n%s\n\n",
                    $error->SeverityCode === Trading\Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                    $error->ShortMessage,
                    $error->LongMessage
                );
            }
        }

        if ($response->Ack !== 'Failure') 
        {
            return $response->Item;
        }
    }

    static function updateQuantity($user, $siteID, $id, $qty)
    {
        self::prodInitConfig($user, $siteID);
        self::prodInitTrading($siteID);

        $request = new Trading\Types\ReviseFixedPriceItemRequestType();

        $request->RequesterCredentials = new Trading\Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = self::$config['production']['authToken'];

        $item = new Trading\Types\ItemType();
        $item->ItemID = (string) $id;

        $item->Quantity = $qty; // for now

        $request->Item = $item;

        $response = self::$service->reviseFixedPriceItem($request);

        if (isset($response->Errors)) {
            foreach ($response->Errors as $error) {
                printf(
                    "%s: %s\n%s\n\n",
                    $error->SeverityCode === Trading\Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                    $error->ShortMessage,
                    $error->LongMessage
                );
            }
        }

        if ($response->Ack !== 'Failure') 
        {
            return true;
        }
    }

    static function updatePrice($user, $siteID, $id, $price)
    {
        self::prodInitConfig($user, $siteID);
        self::prodInitTrading($siteID);

        $request = new Trading\Types\ReviseFixedPriceItemRequestType();

        $request->RequesterCredentials = new Trading\Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = self::$config['production']['authToken'];

        $item = new Trading\Types\ItemType();
        $item->ItemID = (string) $id;

        $item->SellingStatus->CurrentPrice->value = $price; // make sure

        $request->Item = $item;

        $response = self::$service->reviseFixedPriceItem($request); 

        if (isset($response->Errors)) {
            foreach ($response->Errors as $error) {
                printf(
                    "%s: %s\n%s\n\n",
                    $error->SeverityCode === Trading\Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                    $error->ShortMessage,
                    $error->LongMessage
                );
            }
        }

        if ($response->Ack !== 'Failure') 
        {
            return true;
        }
    }

    static function getStore($user, $siteID)
    {
        self::prodInitConfig($user, $siteID);
        self::prodInitTrading($siteID);

        $request = new Trading\Types\GetStoreRequestType();
        $request->RequesterCredentials = new Trading\Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = self::$config['production']['authToken'];

        $response = self::$service->getStore($request);

        if (isset($response->Errors)) {
            foreach ($response->Errors as $error) {
                printf(
                    "%s: %s\n%s\n\n",
                    $error->SeverityCode === Trading\Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                    $error->ShortMessage,
                    $error->LongMessage
                );
            }
        }

        if ($response->Ack !== 'Failure') 
        {    
            return $response;
        }
    }

    static function setEbayCategory($action, $category_id, $category_name, $parent_id, $siteID) // revise user
    {
        self::prodInitConfig(Auth::user()); // $user
        self::prodInitTrading($siteID);

        $request = new Trading\Types\SetStoreCategoriesRequestType();
        $request->RequesterCredentials = new Trading\Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = self::$config['production']['authToken'];


        $request->StoreCategories = new Trading\Types\StoreCustomCategoryArrayType();
        $category = new Trading\Types\StoreCustomCategoryType();
        $request->Action = $action;
        
        if ($action == 'Rename' || $action == 'Delete') {
            $category->CategoryID = (integer) $category_id;    
        }
        $category->Name = $category_name;


        // $request->ItemDestinationCategoryID = 14122;
        if ($parent_id && $parent_id != 1 ) {
            $request->DestinationParentCategoryID = (integer) $parent_id;
        }

        $request->StoreCategories->CustomCategory[] = $category;
        $response = self::$service->SetStoreCategories($request);

        if (isset($response->Errors)) {
            foreach ($response->Errors as $error) {
                printf(
                    "%s: %s\n%s\n\n",
                    $error->SeverityCode === Trading\Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                    $error->ShortMessage,
                    $error->LongMessage
                );
            }
        }

        if ($response->Ack !== 'Failure') {
            printf(
                "Status: %s\nTask ID: %s\n",
                $response->Status,
                $response->TaskID
            );
        }
    }

    static function getProductDetails($user, $siteID, $pid, $idType)
    {
        self::prodInitConfig($user, $siteID);
        self::prodInitProduct($siteID);

        $request = new Product\Types\GetProductDetailsRequest();
        $request->productDetailsRequest [] = new Product\Types\ProductDetailsRequestType();
        $request->productDetailsRequest->dataset = ['DisplayableProductDetails'];
        $request->productDetailsRequest[0]->productIdentifier = new Product\Types\ProductIdentifier();
        
        switch ($idType) {
            case 'ePID':
                $request->productDetailsRequest[0]->productIdentifier->ePID = $pid; 
                break;
            case 'ISBN':
                $request->productDetailsRequest[0]->productIdentifier->ISBN = $pid; 
                break;
            case 'UPC':
                $request->productDetailsRequest[0]->productIdentifier->UPC = $pid; 
                break;
            case 'EAN':
                $request->productDetailsRequest[0]->productIdentifier->EAN = $pid; 
                break;
        }
        
        $response = self::$service->getProductDetails($request);

        if (isset($response->errorMessage)) {
        foreach ($response->errorMessage->error as $error) {
            printf(
                    "%s: %s\n\n",
                    $error->severity=== Product\Enums\ErrorSeverity::C_ERROR ? 'Error' : 'Warning',
                    $error->message
                );
            }
        }

        if ($response->ack !== 'Failure') {
            return $response;
        }
    }

    static function getItemsByProduct($user, $siteID, $pid, $idType) // useful in pricing
    {
        self::prodInitConfig($user, $siteID);
        self::prodInitFinding($siteID);

        $request = new Finding\Types\FindItemsByProductRequest();

        $productId = new Finding\Types\ProductId();
        $productId->value = $pid;
        $productId->type = $idType; // UPC, ReferenceID, EAN
        
        $request->productId = $productId;
        $response = self::$service->findItemsByProduct($request);

        if (isset($response->errorMessage)) 
        {
            foreach ($response->errorMessage->error as $error) 
            {
                printf(
                    "%s: %s\n\n",
                    $error->severity=== Finding\Enums\ErrorSeverity::C_ERROR ? 'Error' : 'Warning',
                    $error->message
                );
            }
        }

        if ($response->ack !== 'Failure') 
        {
            return $response;
        }
    }

    static function addToEbayWithUPC($item, $user)
    {
        self::initConfig($user);
        self::initTrading();

        $request = new Trading\Types\AddFixedPriceItemRequestType();

        $request->RequesterCredentials = new Trading\Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = self::$config['production']['authToken'];

        $ebayItem = new Trading\Types\ItemType();

        $ebayItem->ListingType = Trading\Enums\ListingTypeCodeType::C_FIXED_PRICE_ITEM;
        $ebayItem->Quantity = $item['quantityAvailable'];
        
        $ebayItem->Storefront = new Trading\Types\StorefrontType();
        $ebayItem->Storefront->StoreCategoryID = (int) $item['storeCategoryId'];
        $ebayItem->Storefront->StoreCategory2ID = 0;

        $ebayItem->ListingDuration = Trading\Enums\ListingDurationCodeType::C_GTC;
        $ebayItem->StartPrice = new Trading\Types\AmountType([
            'value' => (double) $item['marketPlaces']['ebay']['ebay-price']
        ]);
        $ebayItem->Country = $item['country'];
        $ebayItem->Currency = 'USD';
        $ebayItem->ConditionID = (int) $item['condition'];
        // $ebayItem->ConditionDescription = $item['conditionDescription'];
        $ebayItem->PostalCode = $item['postalCode'];
        $ebayItem->ProductListingDetails = new Trading\Types\ProductListingDetailsType();
        $ebayItem->ProductListingDetails->UPC = $item['upc'];
        
        // shipping
        $ebayItem->ShippingDetails = new Trading\Types\ShippingDetailsType();
        // $ebayItem->ShippingDetails->ShippingType = Trading\Enums\ShippingTypeCodeType::C_FLAT;
        if ($item['shippingType'] == 'Free') {
            $ebayItem->ShippingDetails->ShippingType = 'Flat'; // flat, free
        }

        $shippingService = new Trading\Types\ShippingServiceOptionsType();
        $shippingService->ShippingServicePriority = 1; // comment this
        $shippingService->ShippingService = 'USPSParcel';// $item['shippingService']; // usps, fedex
        $shippingService->FreeShipping = true;
        $shippingService->ShippingServiceAdditionalCost = new Trading\Types\AmountType(['value' => 0.0]);
        // if (!$item['shippingService'] == 'Free') {
        //     $shippingService->ShippingServiceCost = new Trading\Types\AmountType(['value' => (double) $item['shippingCost']]);
        //     $shippingService->ShippingServiceAdditionalCost = new Trading\Types\AmountType(['value' => (double) $item['shippingAdditionalCost']]);
        // }

        $ebayItem->ShippingDetails->ShippingServiceOptions[] = $shippingService;

        // payment method
        $ebayItem->PaymentMethods = $item['payment'];
        $ebayItem->PayPalEmailAddress = $item['paypalEmail']; // get from user
        $ebayItem->DispatchTimeMax = 1; // input

        // Returns
        $ebayItem->ReturnPolicy = new Trading\Types\ReturnPolicyType();
        if ($item['returns'] == 'ReturnsAccepted') {
            $ebayItem->ReturnPolicy->ReturnsAcceptedOption = 'ReturnsAccepted';
            $ebayItem->ReturnPolicy->RefundOption = 'MoneyBack';
            $ebayItem->ReturnPolicy->ReturnsWithinOption = 'Days_14';
            $ebayItem->ReturnPolicy->ShippingCostPaidByOption = 'Buyer';
        }
        else{
            $ebayItem->ReturnPolicy->ReturnsAcceptedOption = 'ReturnsNotAccepted';
        }


        $request->Item = $ebayItem;
        $response = self::$service->addFixedPriceItem($request);

        if (isset($response->Errors)) {
            foreach ($response->Errors as $error) {
                printf(
                    "%s: %s\n%s\n\n",
                    $error->SeverityCode === Trading\Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                    $error->ShortMessage,
                    $error->LongMessage
                );
            }
        }

        if ($response->Ack !== 'Failure') {
            return $response->ItemID;
            // return self::saveSPier($response->ItemID);
        }
    }

    static function ebayDetails()
    {
        self::initConfig();
        self::initTrading();

        $request = new Trading\Types\GeteBayDetailsRequestType();

        $request->RequesterCredentials = new Trading\Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = self::$config['sandbox']['authToken'];
        
        $request->DetailName = ['CountryDetails','ShippingServiceDetails','ShippingCarrierDetails','ReturnPolicyDetails'];

        $response = self::$service->geteBayDetails($request); 

        if (isset($response->Errors)) {
            foreach ($response->Errors as $error) {
                printf(
                    "%s: %s\n%s\n\n",
                    $error->SeverityCode === Trading\Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                    $error->ShortMessage,
                    $error->LongMessage
                );
            }
        }

        if ($response->Ack !== 'Failure') {
              return $response;
        }      
        
    }

    static function saveSPier($itemId)
    {

    }
   
    static function testAdd()
    {
        self::initConfig();
        self::initTrading();

        $request = new Trading\Types\AddFixedPriceItemRequestType();

        $request->RequesterCredentials = new Trading\Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = self::$config['sandbox']['authToken'];

        $item = new Trading\Types\ItemType();

        $item->ListingType = Trading\Enums\ListingTypeCodeType::C_FIXED_PRICE_ITEM;
        $item->Quantity = 9;

        $item->ListingDuration = Trading\Enums\ListingDurationCodeType::C_GTC;

        $item->StartPrice = new Trading\Types\AmountType(['value' => 19.99]);

        $item->ProductListingDetails = new Trading\Types\ProductListingDetailsType();
        $item->ProductListingDetails->UPC = '885909708260';
                
        $item->SKU = 'ABC-0024';
        $item->Country = 'US';
        // $item->Location = 'Beverly Hills';
        $item->PostalCode = '90210';
        $item->Currency = 'USD';

        $item->ConditionID = 1000;


        $item->PaymentMethods = [
            'VisaMC',
            'PayPal'
        ];
        $item->PayPalEmailAddress = 'my@hotmail.com';
        $item->DispatchTimeMax = 1;

        $item->ShippingDetails = new Trading\Types\ShippingDetailsType();
        $item->ShippingDetails->ShippingType = Trading\Enums\ShippingTypeCodeType::C_FLAT;

        $shippingService = new Trading\Types\ShippingServiceOptionsType();
        $shippingService->ShippingServicePriority = 1;
        $shippingService->ShippingService = 'USPSParcel';
        $shippingService->FreeShipping = true;
        // $shippingService->ShippingServiceCost = new Trading\Types\AmountType(['value' => 2.00]);
        // $shippingService->ShippingServiceAdditionalCost = new Trading\Types\AmountType(['value' => 1.00]);
        $item->ShippingDetails->ShippingServiceOptions[] = $shippingService;

        $item->ReturnPolicy = new Trading\Types\ReturnPolicyType();
        $item->ReturnPolicy->ReturnsAcceptedOption = 'ReturnsAccepted';
        $item->ReturnPolicy->RefundOption = 'MoneyBack';
        $item->ReturnPolicy->ReturnsWithinOption = 'Days_14';
        $item->ReturnPolicy->ShippingCostPaidByOption = 'Buyer';
        $request->Item = $item;
        $response = self::$service->addFixedPriceItem($request);

        if (isset($response->Errors)) {
            foreach ($response->Errors as $error) {
                printf(
                    "%s: %s\n%s\n\n",
                    $error->SeverityCode === Trading\Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                    $error->ShortMessage,
                    $error->LongMessage
                );
            }
        }

        if ($response->Ack !== 'Failure') {
            printf(
                "The item was listed to the eBay Sandbox with the Item number %s\n",
                $response->ItemID
            );
        }
    }

    static function getSales($user, $siteID, $initial = false, $startDate = null)
    {
        self::prodInitConfig($user, $siteID);
        self::prodInitTrading($siteID);

        $Orders = [];        

        $request = new Trading\Types\GetOrdersRequestType();
         
        $request->RequesterCredentials = new Trading\Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = self::$config['production']['authToken'];

        $timeFrom = Carbon::now();
        
        if ($initial) 
        {
            $timeFrom->startOfMonth();
        }
        else if (isset($startDate))
        {
            $timeFrom = $startDate;
        }
        else
        {
            $timeFrom->subMinutes(60);
        } 
         
        $request->CreateTimeFrom = date_create($timeFrom);
        $request->CreateTimeTo = date_create();
        $request->DetailLevel = ['ReturnAll'];
        $request->Pagination = new Trading\Types\PaginationType();
        $pageNumber = 1;
        
        do
        {
            $request->Pagination->PageNumber = $pageNumber;
            $response = self::$service->getOrders($request);

            if (isset($response->Errors)) 
            {
                foreach ($response->Errors as $error) 
                {
                    printf(
                        "%s: %s\n%s\n\n",
                        $error->SeverityCode === Trading\Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                        $error->ShortMessage,
                        $error->LongMessage
                    );
                }
            }

            if ($response->Ack !== 'Failure') 
            {
                foreach ($response->OrderArray->Order as $order) 
                {
                    $Orders [] = $order;
                    // print_r($order);
                }
            }

            $pageNumber ++;
        }
        while (isset($response->OrderArray) && $pageNumber <= $response->PaginationResult->TotalNumberOfPages);

        return $Orders;
    }

    static function sessionID($user, $siteID)
    {
        self::prodInitConfig($user, $siteID);
        self::prodInitTrading($siteID);

        $request = new Trading\Types\GetSessionIDRequestType();
        $request->RuName = 'Yamen_Ashraf-YamenAsh-Pierga-wlrzjuu';

        $response = self::$service->getSessionID($request);

        if (isset($response->Errors)) 
        {
            foreach ($response->Errors as $error) 
            {
                printf(
                    "%s: %s\n%s\n\n",
                    $error->SeverityCode === Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                    $error->ShortMessage,
                    $error->LongMessage
                );
            }
        }

        if ($response->Ack !== 'Failure') 
        {
            return $response->SessionID;
        }
    }

    static function fetchToken($user, $siteID, $sessionId)
    {
        self::prodInitConfig($user, $siteID);
        self::prodInitTrading($siteID);

        $request = new Trading\Types\FetchTokenRequestType();
        $request->SessionID = $sessionId;

        $response = self::$service->fetchToken($request);

        if (isset($response->Errors)) 
        {
            foreach ($response->Errors as $error) 
            {
                printf(
                    "%s: %s\n%s\n\n",
                    $error->SeverityCode === Trading\Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                    $error->ShortMessage,
                    $error->LongMessage
                );
            }
        }

        if ($response->Ack !== 'Failure') 
        {
            return $ebayAuth = [ 
                'token' => $response->eBayAuthToken,
                'exp' => $response->HardExpirationTime
            ];
        }
    }

    static function getDashboard($user, $siteID)
    {
        self::prodInitConfig($user, $siteID);
        self::prodInitTrading($siteID);

        $request = new Trading\Types\GetSellerDashboardRequestType();
         
        $request->RequesterCredentials = new Trading\Types\CustomSecurityHeaderType();
        $request->RequesterCredentials->eBayAuthToken = self::$config['production']['authToken'];

        $response = self::$service->getSellerDashboard($request);

        if (isset($response->Errors)) 
        {
            foreach ($response->Errors as $error) 
            {
                printf(
                    "%s: %s\n%s\n\n",
                    $error->SeverityCode === Trading\Enums\SeverityCodeType::C_ERROR ? 'Error' : 'Warning',
                    $error->ShortMessage,
                    $error->LongMessage
                );
            }
        }

        if ($response->Ack !== 'Failure') 
        {
            return $response;
        }
    }

    static function getSiteID($site)
    {
        switch ($site) 
        {
            case 'Ebay-us':
                $siteID = Constants\SiteIds::US;
                break;
            case 'Ebay-ca':
                $siteID = Constants\SiteIds::ENCA;
                break;
            case 'Ebay-uk':
                $siteID = Constants\SiteIds::GB;
                break;
            case 'Ebay-au':
                $siteID = Constants\SiteIds::AU;
                break;
            case 'Ebay-at':
                $siteID = Constants\SiteIds::AT;
                break;
            case 'Ebay-fr':
                $siteID = Constants\SiteIds::FR;
                break;
            case 'Ebay-de':
                $siteID = Constants\SiteIds::DE;
                break;
            case 'Ebay-it':
                $siteID = Constants\SiteIds::IT;
                break;
            case 'Ebay-motors':
                $siteID = Constants\SiteIds::MOTORS;
                break;
            default: $siteID = null;
                break;
        }

        return (string)$siteID;
    }

    static function getSite($siteID)
    {
        switch (Auth::user()->setting->siteID)
        {
            case 0:
                $site = 'Ebay-us';
                break;
            case 100:
                $site = 'Ebay-motors';
                break;
            // rest of ebay sites
        }

        return $site;
    }

    static function getSiteCurrency($siteID)
    {
        switch ($siteID) 
        {
            case 0: // us
                return 'USD';
                break;
            case 100: // motors
                return 'USD';
                break;

        // AUD Australian Dollar. Australia site (global ID EBAY-AU, site ID 15).
        // CAD Canadian Dollar. Canada site (global ID EBAY-ENCA, site ID 2) (Items listed on the Canada site can also specify USD.)
        // CHF Swiss Franc. Switzerland site (global ID EBAY-CH, site ID 193).
        // CNY Chinese Chinese Renminbi.
        // EUR Euro. e sites: Austria (global ID EBAY-AT, site 16), Belgium_French (global ID EBAY-FRBE, site 23), France (global ID EBAY-FR, site 71), Germany (global ID EBAY-DE, site 77), Italy (global ID EBAY-IT, site 101), Belgium_Dutch (global ID EBAY-NLBE, site 123), Netherlands (global ID EBAY-NL, site 146), Spain (global ID EBAY-ES, site 186), Ireland (global ID EBAY-IE, site 205).
        // GBP Pound Sterling. UK site (global ID EBAY-GB, site ID 3).
        // HKD Hong Kong Dollar. Hong Kong site (global ID EBAY-HK, site ID 201).
        // INR Indian Rupee. India site (global ID EBAY-IN, site ID 203).
        // MYR Malaysian Ringgit. Malaysia site (global ID EBAY-MY, site ID 207).
        // PHP Philippines Peso. Philippines site (global ID EBAY-PH, site ID 211).
        // PLN Poland, Zloty. Poland site (global ID EBAY-PL, site ID 212).
        // SEK Swedish Krona. Sweden site (global ID EBAY-SE, site 218).
        // SGD Singapore Dollar. Singapore site (global ID EBAY-SG, site 216).
        // TWD New Taiwan Dollar. Note that there is no longer an eBay Taiwan site.
        // USD US Dollar. US (site ID 0), eBayMotors (site 100), and Canada (site 2) sites.
        }
    }

    static function getCountry($site) //revise
    {
        switch ($site) 
        {
            case 'Ebay-us':   return 'USA';
            case 'Ebay-ca':   return 'CAN';
            case 'Ebay-mx':   return 'MEX'; 
            case 'Ebay-br':   return 'BRA';
            case 'Ebay-de':   return 'DEU';
            case 'Ebay-es':   return 'ESP';
            case 'Ebay-fr':   return 'FRA';
            case 'Ebay-it':   return 'ITA';
            case 'Ebay-uk':   return 'GBR';
            case 'Ebay-in':   return 'IND';
            case 'Ebay-jp':   return 'JPN';
            case 'Ebay-cn':   return 'CHN';
        }
    }

    static function getTimezone($site)
    {
        # code...
    }
}