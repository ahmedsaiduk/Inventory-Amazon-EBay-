<?php 

namespace App\Http\Controllers\EbayTraits;

use App\Ebay;
use App\StoreCategory;

trait SyncEbayCategories
{
	protected function SyncEbayCategories($user, $siteID)
	{
		$response = Ebay::getStore($user, $siteID);
        
        foreach ($response->Store->CustomCategories->CustomCategory as $category) // first level
        {
            $parent = $user->store_categories()->updateOrCreate(['name' => $category->Name],[
                'ebay_category_id' => $category->CategoryID,
                'order' => $category->Order,
                'level' => 1
            ]); 

            if (isset($category->ChildCategory)) // second level - revise this
            {   
                foreach ($category->ChildCategory as $child_category) 
                {
                    $user->store_categories()->updateOrCreate(['name' => $child_category->Name],[
                        'ebay_category_id' => $child_category->CategoryID,
                        'order' => $child_category->Order,
                        'parent_category_id' => $parent->id,
                        'level' => 2
                    ]);  
                }
            }   
        }
	}
}