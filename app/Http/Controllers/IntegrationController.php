<?php

namespace App\Http\Controllers;

use Auth;
use App\Ebay;
use App\Amazon;
use App\Integration;
use App\Jobs\SyncEbayStore;
use Illuminate\Http\Request;
use App\Jobs\SyncEbayOrders;
use App\Jobs\SyncAmazonItems;
use App\Jobs\CleanSpierItems;
use App\Jobs\SyncAmazonOrders;
use Illuminate\Support\Facades\Redirect;

class IntegrationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    use RefreshIntegrationTrait;
    
    public function index()
    {
        $integrations = Auth::user()->integrations;
        
        return view('integrations.index', compact('integrations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
         
        return view('integrations.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        if ($request->has('amazon'))
        {
            $this->validate($request, [
                'sellerID' => 'required',
                'authToken' => 'required',
                'site' => 'required'
            ]);

            $siteID = Amazon::getSiteID($request->site);

            if (Amazon::checkCredentials($siteID, $request->sellerID, $request->authToken)) 
            {
                $currency = Amazon::getSiteCurrency($siteID);

                Auth::user()->integrations()->updateOrCreate(['siteID' => $siteID],[
                    'marketPlace' => 'Amazon',
                    'site' => $request->site,
                    'sellerID' => $request->sellerID,
                    'authToken' => $request->authToken,
                    'currency' => $currency,
                    'packingSlipURL' => 'https://sellercentral.amazon.com/gp/orders-v2/packing-slip/ref=ag_myopack_cont_myo?ie=UTF8&orderID=',
                    'country' => Amazon::getCountry($request->site),
                    // 'timezone' => Amazon::getTimezone($request->site)
                ]);

                $this->setPrimarySite($request->site);
                
                $job = (new SyncAmazonItems(Auth::user(), $siteID))->onQueue('users');
                dispatch($job);

                $job = (new SyncAmazonOrders(Auth::user(), $siteID, true))->onQueue('users');
                dispatch($job);

                $notification = [
                    'color' => 'green',
                    'message' => 'SellerPier is connected to your store successfully, Please wait while it loads.'
                ];

                return Redirect::to('/integrations')->with([
                    'color' => $notification['color'],
                    'message' => $notification['message']
                ]);
            }

            $notification = [
                'color' => 'red',
                'message' => 'Oops! something went wrong with your credentials, Please try again later.'
            ];

            return Redirect::to('/integrations')->with([
                'color' => $notification['color'],
                'message' => $notification['message']
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Integration $integration)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Integration $integration)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Integration $integration)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Integration $integration)
    {
        //
    }

    public function ebay(Request $request)
    {
        if ($request->has('ebay')) 
        {
            if ($request->site == 'motors') 
            {
                // check if integrated with ebay and add integration  
                $ebayIntegration = Auth::user()->integrations()->where('marketPlace', 'Ebay')->first();

                if (isset($ebayIntegration)) 
                {
                     Auth::user()->integrations()->updateOrCreate(['siteID' => '100'],[
                        'marketPlace' => 'Ebay',
                        'site' => 'Ebay-motors',
                        'sellerID' => $ebayIntegration->sellerID,
                        'authToken' => $ebayIntegration->authToken,
                        'currency' => $ebayIntegration->currency,
                        'timezone' => $ebayIntegration->timezone,
                        'tokenEXP' => $ebayIntegration->tokenEXP,
                        'country' => $ebayIntegration->country,
                        'packingSlipURL' => $ebayIntegration->packingSlipURL
                    ]);

                    $notification = [
                        'color' => 'green',
                        'message' => 'SellerPier is connected to your store successfully, Please wait while it loads.'
                    ];

                    return Redirect::to('/integrations')->with([
                        'color' => $notification['color'],
                        'message' => $notification['message']
                    ]);
                }
                else
                {
                    $notification = [
                        'color' => 'red',
                        'message' => 'Oops! Please connect to your Ebay store first.'
                    ];

                    return Redirect::to('/integrations')->with([
                        'color' => $notification['color'],
                        'message' => $notification['message']
                    ]);
                }
            }
            else
            {
                if ($request->site == 'us') 
                {
                    $end = 'com';
                }
                else
                {
                    $end = $request->site;
                }

                $siteID = Ebay::getSiteID('Ebay-'.$request->site);

                $ebaySessionId = Ebay::sessionID(Auth::user(), $siteID);
                
                Auth::user()->setting()->update([
                    'sessionID' => $ebaySessionId,
                    'siteID' => $siteID[0]
                ]);
                
                return Redirect::to('https://signin.ebay.'.$end.'/ws/eBayISAPI.dll?SignIn&runame=Yamen_Ashraf-YamenAsh-Pierga-wlrzjuu&SessID='.$ebaySessionId);
            }
        }
    }

    public function ebayToken()
    {
        $ebayAuth = Ebay::fetchToken( Auth::user(), Auth::user()->setting->siteID, Auth::user()->setting->sessionID);

        $currency = Ebay::getSiteCurrency(Auth::user()->setting->siteID);

        $site = Ebay::getSite(Auth::user()->setting->siteID);

        $integration = Auth::user()->integrations()->updateOrCreate(['siteID' => Auth::user()->setting->siteID],[
            'marketPlace' => 'Ebay',
            'site' => $site,
            'authToken' => $ebayAuth['token'],
            'tokenEXP' => $ebayAuth['exp'],
            'currency' => $currency,
            'packingSlipURL' => 'https://payments.ebay.com/ws/eBayISAPI.dll?PrintPostage&itemId=&transactionId=',
            'country' => Ebay::getCountry($site),
            // 'timezone' => Ebay::getTimzone($site),
            // sellerID
        ]);

        $this->setPrimarySite($site);

        if ($integration) 
        {
            $notification = [
                'message' => 'You have integrated your eBay store successfully!',
                'type' => 'success'
            ];
            
            $job = (new SyncEbayStore(Auth::user(), Auth::user()->setting->siteID))->onQueue('users');
            dispatch($job);
            
            $job = (new SyncEbayOrders(Auth::user(), Auth::user()->setting->siteID, true))->onQueue('users');
            dispatch($job);

            $notification = [
                'color' => 'green',
                'message' => 'SellerPier is connected to your store successfully, Please wait while it loads.'
            ];

            return Redirect::to('/integrations')->with([
                'color' => $notification['color'],
                'message' => $notification['message']
            ]);    
        }
    }

    public function setPrimarySite($site)
    {
        $primarySite = Auth::user()->setting->primarySite;
        
        if (!isset($primarySite)) 
        {
            $setPrimary = Auth::user()->setting()->update(['primarySite' => $site]);
        }
    }

    public function refresh(Integration $integration)
    {
        $this->RefreshIntegration($integration);

        $notification = [
            'color' => 'green',
            'message' => 'Your integration is being refreshed. Please wait...'
        ];

        return Redirect::to('/integrations')->with([
            'color' => $notification['color'],
            'message' => $notification['message']
        ]);
    }

    // public function refreshall() // overuse
    // {
    //     foreach (Auth::user()->integrations as $integration) 
    //     {
    //         $this->RefreshIntegration($integration);
    //     }

    //     dispatch(new CleanSpierItems($integration->user));

    //     $notification = [
    //         'color' => 'green',
    //         'message' => 'Your integrations are being refreshed. Please wait...'
    //     ];

    //     return Redirect::to('/integrations')->with([
    //         'color' => $notification['color'],
    //         'message' => $notification['message']
    //     ]);   
    // }
}
