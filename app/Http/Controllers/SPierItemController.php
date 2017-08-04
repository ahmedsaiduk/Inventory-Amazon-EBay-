<?php

namespace App\Http\Controllers;

use Auth;
use Excel;
use App\Ebay;
use App\BulkFile;
use App\SPierItem;
use App\Attribute;
use App\StoreCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class SPierItemController extends Controller
{
    use EbayTraits\SyncEbayCategories, checkIntegrationsTrait;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $items = Auth::user()->spier_items;
        $integrations = Auth::user()->integrations->load('items');
        
        return view('inventory.items.index', compact('items', 'integrations'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $storeCats = Auth::user()->store_categories()
                                 ->whereNull('parent_category_id')
                                 ->with('sub_categories')
                                 ->get();
        $integrations = $this->checkIntegrations(Auth::user());
        $step = 1;

        if (request()->input('upc')) {
            $upc = request()->input('upc');
            $step = 2;
            $item = $this->getProduct(request()->input('upc'));
            return view('inventory.items.create',compact('step','item','storeCats','integrations','upc'));    
        }

        return view('inventory.items.create',compact('step'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    { 
        $this->validate($request, [
            'sku' => 'required|max:191',
            'storeCategoryId' => 'required',
            'condition' => 'required',
            'quantityAvailable' => 'required|numeric',
            'country' => 'required',
            'postalCode' => 'required|numeric|min:10000|max:99999',
            // 'currency' => 'required',
            'shippingType' => 'required',
            'returns' => 'required',
            'payment' => 'required',
            'paypalEmail' => 'required|max:191'
        ]);

        // revise
        if (!$this->checkSku(request()->input('sku'))) {
            // error repeated sku
            return 'error : repeated sku';
        }
        else {
            $item = $this->item();
            // return Ebay::addToEbayWithUPC($item); // publish to ebay

            $spItem = Auth::user()->spier_items()->create($item);
            $st_cat = Auth::user()->store_categories()->findOrFail(request()->input('storeCategoryId'));

            $spItem->store_category()->associate($st_cat);
            $spItem->save();
            $this->marketPlaces('store', $spItem);

            return Redirect::to('/items');

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $spierItem = Auth::user()->spier_items()->find($id);
        
        if ($spierItem) {
            $spierItem->load('attributes','store_category','spec_category');
            $marketPlaces = $spierItem->marketPlaces();
            return view('inventory.items.show', compact('spierItem','marketPlaces'));
            // return $markets['ebay']['price'];
        }
        else{
            return back();
        }
    }

    public function item()
    {
        $integrations = $this->checkIntegrations(Auth::user());

        foreach ($integrations as $integration) 
        {
            $marketPlaces[$integration] = [
                $integration.'-price' => request()->input($integration.'-price'),
                $integration.'-pricePreferred' => request()->input($integration.'-pricePreferred'),
                $integration.'-priceMin' => request()->input($integration.'-priceMin'),
                $integration.'-priceMax' => request()->input($integration.'-priceMax'),
                $integration.'-shipping' => request()->input($integration.'-shipping')
            ];
        }
        // condition new not 1000
        return $item = [
            'sku' => request()->input('sku'),
            'upc' => request()->input('upc'),
            'storeCategoryId' => request()->input('storeCategoryId'),
            'condition' => request()->input('condition'), // update condition
            'conditionDescription' => request()->input('conditionDescription'),
            'quantityAvailable' => (int) request()->input('quantityAvailable'),
            'country' => request()->input('country'),
            'postalCode' => request()->input('postalCode'),
            'cost' => (double) request()->input('cost'),
            'mapPrice' => (double) request()->input('mapPrice'),
            'priceRetail' => (double) request()->input('priceRetail'),
            // 'currency' => request()->input('currency'),
            'shippingType' => request()->input('shippingType'),
            'returns' => request()->input('returns'),
            'payment' => request()->input('payment'),
            'paypalEmail' => request()->input('paypalEmail'),
            'marketPlaces' => $marketPlaces
        ];
    }

    public function marketPlaces($action, $spierItem)
    {
        $integrations = $this->checkIntegrations(Auth::user());
        $spItem = $spierItem;

        foreach ($integrations as $integration) 
        {
            $marketPlaces = [
                    $integration.'-price' => request()->input($integration.'-price'),
                    $integration.'-pricePreferred' => request()->input($integration.'-pricePreferred'),
                    $integration.'-priceMin' => request()->input($integration.'-priceMin'),
                    $integration.'-priceMax' => request()->input($integration.'-priceMax'),
                    $integration.'-shipping' => request()->input($integration.'-shipping')
                ];
            if (!empty($marketPlaces)) {
                switch ($integration) {
                    
                    case 'amazon':
                        # code...
                        break;
                    
                    case 'ebay':
                        
                        if ($action == 'store') {
                            $ebayItem = new Ebay;
                        }
                        else {
                            $ebayItem = $spItem->ebay_item;
                        }
                        
                        $ebayItem->price = $marketPlaces[$integration.'-price'];
                        $ebayItem->pricePreferred = $marketPlaces[$integration.'-pricePreferred'];
                        $ebayItem->priceMin = $marketPlaces[$integration.'-priceMin'];
                        $ebayItem->priceMax = $marketPlaces[$integration.'-priceMax'];
                        $ebayItem->shipping = $marketPlaces[$integration.'-shipping'];
                        $ebayItem->currency = 'USD'; // currency from model 'site ID'

                        $spItem->ebay_item()->save($ebayItem);
                        
                        break;

                    case 'magento':
                        # code...
                        break;
                    
                    case 'jet':
                        # code...
                        break;
                    
                    case 'walmart':
                        # code...
                        break;
                                    
                    default:
                        # code...
                        break;
                } 
            } 
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $spierItem = Auth::user()->spier_items()->find($id);

        if ($spierItem) {
            $spierItem->load('attributes','store_category','spec_category','ebay_item');
            $integrations = $this->checkIntegrations(Auth::user());
            $marketPlaces = $spierItem->marketPlaces();
            return view('inventory.items.edit', compact('spierItem','integrations','marketPlaces'));
            // return $spierItem;
        }
        else{
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) // expected error
    {
        $spierItem = Auth::user()->spier_items()->find($id);

        // validate request

        if ($spierItem) {
            $item = $this->item();
            $spierItem->update($item);
            $this->marketPlaces('update', $spierItem);

            foreach ($spierItem->attributes as $attribute) 
            {
                $attribute->value = request()->input($attribute->name);
                $spierItem->attributes()->save($attribute);
            }

            return Redirect::to('/items'); // expected error
        }
        else{
            // handle error       
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $spierItem = Auth::user()->spier_items()->find($id);

        if ($spierItem) {
            $spierItem->delete();
            // delete from ebay
            return Redirect::to('/items');    
        }
        else{
            return back();
        }
    }

    public function publish()
    {
        return $unpublished = Auth::user()->spier_items()->ebay_item;
                                   // ->whereNull('published')
                                   // ->orWhere('published', false)
                                   // ->get();
        // return view('inventory.items.publish');
    }

    public function checkSku($sku)
    {
        $check = Auth::user()->spier_items()->where('sku',$sku)->get();

        if (empty($check)) {
            return 'true';
        }
        else{
            return 'false';
        }
    }

    public function getProduct($pid)
    {
        // return Ebay::getProductDetails($pid);
        $response = Ebay::getItemByProduct($pid);

        $item = [
            'title' => $response->searchResult->item[0]->title,
            'imgURL' => $response->searchResult->item[0]->galleryURL
        ];

        return $item;
    }

    public function getDetails()
    {
        return Ebay::ebayDetails();
    }

    public function downloadSpecs()
    {
        Ebay::createFile(request()->input('catId'));
        return Redirect::to('/items');
    }

    public function getFile(Request $request)
    {
        $exts = ['xls','xlsx','csv'];

        if ($request->hasFile('dataFile')) 
        {
            $inputFile = $request->file('dataFile');
            if ($inputFile->isValid()) 
            {   
                if (in_array($inputFile->extension(), $exts)) // not working with xls
                {
                    $data = Excel::load($inputFile)->get();
                    $filename = time().'_'.Auth::user()->name.'.'.$inputFile->extension();
                    $path = $inputFile->storeAs('/uploads/bulks/', $filename);
                    
                    $file = new BulkFile(['file' => $filename]);
                    Auth::user()->bulkFiles()->save($file);
                    
                    return $this->getFileDate($filename);
                }
                else{
                    $notification = [
                        'message' => 'please upload a file with supported extensions "xls, xlsx or csv"'
                    ];
                    // $catsResult = null;
                    // return back()->with(['notification' => $notification,'catsResult' => $catsResult]);

                    return back();    
                }
            }
            else{
                $notification = [
                        'message' => 'please upload a valid file"'
                    ];
                return back();
                // return back()->with(['notification' => $notification]);
            }

        }
        return back();
        // return Redirect::to('/items');
    }

    public function getFileDate($filename)
    {
        $data = Excel::load('storage/app/uploads/bulks/' . $filename )->get();
        unlink(storage_path('app/uploads/bulks/' . $filename ));
        return $this->interpret($data);
     //    $notification = [
     //        'message' => 'now go to inventory for updates!',
     //        'title' => 'File uploaded successfully',
     //        'type' => 'success'
     //    ];

     //    return view('listings/manage', compact('notification'));
    }

    public function interpret($data)
    {
        if ($this->checkFile($data)) 
        {
            foreach ($data as $item) 
            {
                // echo $item->site."<br>";
                // echo $item->action."<br>";
                $this->checkSiteAction($item);
            }
            // return $data;
            // return $this->itemsAdd();
            
            // if (!empty($itemsToAdd)) {
            //     # code...
            // }

            // if (!empty($itemsToModify)) {
            //     # code...
            // }
        }

        else{
            // error file handling ...
        }
    }

    public function checkFile($data)
    {
        // check for errors
        // check integrations, validation

        return true; // for now
    }

    public function checkSiteAction($item)
    {
        $integrations = $this->checkIntegrations(Auth::user());

        if ($item->site == 'inventory') {
            // all store
            switch ($item->action) {
                case 'add':
                    $this->inventoryAdd($item); 
                    break;
                case 'modify':
                    $this->inventoryModify($item);
                    break;
                case 'delete':
                    $this->inventoryDelete($item);
                    break;
                default:
                    // handle invalid action
                    break;
            }
        }
        else {
            if (in_array($item->site, $integrations)) 
            {
                switch ($item->site) 
                {
                    case 'Amazon':
                        # code...
                        break;
                    case 'eBay':
                        switch ($item->action) 
                        {
                            case 'Add':
                               // $itemsToAdd[] = $item;
                               break;
                            case 'Modify':
                               # code...
                               break;
                            case 'End':
                               # code...
                               break;
                           
                            default:
                               // handle action error
                               break;
                        }    
                        break;
                    case 'Magento':
                        # code...
                        break; 
                    case 'Jet':
                        # code...
                        break;
                    case 'Walmart':
                        # code...
                        break;
                    case 'Volusion':
                        # code...
                        break;    
                    
                    default:
                        // site error
                        break;
                }      
            }
            else {
                // integration error
            }
        }
    }

    public function inventoryAdd($item)
    {
        // $this->itemsToAdd [] = $item;

        if (!$this->checkSku(request()->input('sku'))) {
            /// error repeated sku
            return 'error : repeated sku';
        }
        else {
            $spierItem = Auth::user()->spier_items()->create([
                    'fullfilledBy' => Auth::user()->name,
                    'sku' => $item->sku,
                    'upc' => $item->upc,
                    'title' => $item->title,
                    'cost' => $item->cost,
                    'quantityAvailable' => $item->quantity_available,
                    'condition' => $item->condition,
                    'conditionDescription' => $item->condition_description,
                    'description' => $item->description,
                    'vendorName' => $item->vendor_name
                ]);
            $sp_cat = SpecCategory::where('spec_cat_id', $item->spec_category_id)->first();
            $st_cat = StoreCategoryController::find($item->store_category_id);

            $spierItem->spec_category()->associate($sp_cat);
            $spierItem->store_category()->associate($st_cat);
        }
    }

    public function inventoryModify($item)
    {
        // $this->itemsToModify [] = $item;
    }

    public function inventoryDelete($item)
    {
        // $this->itemsToDelete [] = $item;
    }

    public function testAdd()
    {
        return Ebay::testAdd();
    }

    public function renderToEbay()
    {
        // foreach ($specs[0]->NameRecommendation as $attr) 
        // {
        //     $attribute = new Attribute;
        //     $attribute->name = $attr->Name;
        //     $attribute->value = request()->input($attr->Name);
        //     $spItem->attributes()->save($attribute);
        // }

    }

    public function ebayDetails()
    {
        return Ebay::ebayDetails();
    }

    public function ebay_sync()
    {
        return $this->SyncEbayCategories();
    }
}