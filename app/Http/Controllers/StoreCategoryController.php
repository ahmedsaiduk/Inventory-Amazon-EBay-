<?php

namespace App\Http\Controllers;

use Auth;
use App\Ebay;
use App\StoreCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class StoreCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $storeCategories = Auth::user()->store_categories()
                                       ->whereNull('parent_category_id')
                                       ->with('sub_categories')
                                       ->orderBy('order')
                                       ->get();
        return view('inventory.categories.index', compact('storeCategories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // validate request
        
        $store_category = new StoreCategory;
        $store_category->name = $request->input('catName');
        if ($request->input('parentId')) {
            $store_category->parent_category_id = $request->input('parentId');
        }
        Auth::user()->store_categories()->save($store_category);
        Ebay::setEbayCategory('Add',null,$request->input('catName'),$request->input('parentId'));
        
        $this->clean_categories();
        
        return Redirect::to('/categories');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Auth::user()->store_categories()
                                ->where('id',$id)
                                ->first();

        if ($category){
            // return $category->load('sub_categories')->load('spier_items');
            return view('inventory.categories.show', compact('category'));
        }
        else{
            return back();
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
        $category = Auth::user()->store_categories()
                                ->where('id',$id)
                                ->first();

        if ($category) {
            return view('inventory.categories.edit',compact('category'));
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
    public function update(Request $request, $id)
    {
        $category = Auth::user()->store_categories()
                                ->where('id',$id)
                                ->first();
        if ($category) {   
            $category->name = $request->input('catName');
            $category->save();
            Ebay::setEbayCategory('Rename', $category->ebay_category_id, $request->input('catName'), null);
            // renderFromEbay
            return Redirect::to('/categories');
        }
        else{
            return back();
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
        $category = Auth::user()->store_categories()->find($id);
        
        if ($category) {
            $category->delete(); // delete local
            Ebay::setEbayCategory('Delete', $category->ebay_category_id,$category->name, null); // ebay delete
            // renderFromEbay
            return Redirect::to('/categories');
        }
        else{
            return back();
        }
        
    }

    public function clean_categories() // temporarily solution for cleaning storing categories 
    {
        $cats = StoreCategory::whereNull('ebay_category_id')->delete();
    }
}