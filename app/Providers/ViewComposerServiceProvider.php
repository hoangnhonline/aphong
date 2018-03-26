<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Hash;
use App\Models\Settings;
use App\Models\ArticlesCate;
use App\Models\Articles;
use App\Models\District;
use App\Models\CustomLink;
use App\Models\Services;
use App\Models\Menu;
use App\Models\CateParent;
use App\Models\Text;
use App\Models\Product;
use Auth, Session;
class ViewComposerServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot()
	{
		//Call function composerSidebar
		$this->composerMenu();	
		
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		//
	}

	/**
	 * Composer the sidebar
	 */
	private function composerMenu()
	{
		
		view()->composer( '*' , function( $view ){		
			
	        $settingArr = Settings::whereRaw('1')->lists('value', 'name');
	        $articleCate = ArticlesCate::orderBy('display_order', 'desc')->get();	     
	       
	        $tinRandom = Articles::whereRaw(1);
	        if($tinRandom->count() > 0){
	        	$tinRandom = $tinRandom->limit(5)->get();
	        }
	        $footerLink = CustomLink::where('block_id', 1)->orderBy('display_order', 'asc')->get();	        	                	       	
	       	$menuList = Menu::where('menu_id', 1)->orderBy('display_order', 'asc')->get();
	       	$cateParentList = CateParent::orderBy('display_order')->get();       	

	       	$textList = Text::whereRaw('1')->lists('content', 'id');
	        $routeName = \Request::route()->getName();	      
	        
	        $isEdit = Auth::check();	        
	        $servicesList = Services::getList();	        

	        // cart
	        $getlistProduct = $listProductId = [];
	        $arrProductInfo = (object) [];
	        if(Session::has('products') && !empty(Session::get('products'))){	            
	        	$getlistProduct = Session::get('products');
	        	$listProductId = array_keys($getlistProduct);
	        	$arrProductInfo = Product::whereIn('product.id', $listProductId)->get();
	        }
	        $loadDistrict = District::join('branch', 'branch.district_id', '=', 'district.id')->select('district.id', 'district.name', 'district.city_id')->orderBy('branch.display_order')->get();	
	        $listProduct = Product::where('product.status', 1)->orderBy('id', 'desc')->select('slug', 'name', 'id', 'alias')->get(); 
                
			$view->with( [
					'settingArr' => $settingArr, 
					'articleCate' => $articleCate, 
					'tinRandom' => $tinRandom, 					
					'footerLink' => $footerLink,					
					'menuList' => $menuList,
					'cateParentList' => $cateParentList,
					'routeName' => $routeName,
					'textList' => $textList,
					'isEdit' => $isEdit,
					'servicesList' => $servicesList,
					'getlistProduct' => $getlistProduct,
					'arrProductInfo' => $arrProductInfo,
					'listProductId' => $listProductId,
					'loadDistrict' => $loadDistrict,
					'listProduct' => $listProduct
			] );
			
		});
	}
	
}
