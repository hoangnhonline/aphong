<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helpers\Helper;
use App\Models\LandingProjects;
use App\Models\ArticlesCate;
use App\Models\Pages;
use App\Models\Menu;

use DB, Session;
class GeneralController extends Controller
{
    public function updateOrder(Request $request){
        if ($request->ajax())
        {
        	$dataArr = $request->all();
        	$str_order = $dataArr['str_order'];        	
            $table = $dataArr['table'];        
            if( $str_order ){
            	$tmpArr = explode(";", $str_order);
            	$i = 0;
            	foreach ($tmpArr as $id) {
            		if( $id > 0 ){
            			$i++;
            			DB::table($table)
				        ->where('id', $id)				        
				        ->update(array('display_order' => $i));			
            		}
            	}
            }
        }        
    }
    
    public function getSlug(Request $request){
    	$strReturn = '';
    	if( $request->ajax() ){
    		$str = $request->str;
    		if( $str ){
    			$strReturn = str_slug( $str );
    		}
    	}
    	return response()->json( ['str' => $strReturn] );
    }
    public function setupMenu(Request $request){        
        $articlesCateList = ArticlesCate::where('status', 1)->orderBy('display_order', 'asc')->get();
        $pageList = Pages::where('status', 1)->get();
        return view('backend.menu.index', compact( 'landingList', 'articlesCateList', 'pageList'));
    }
    public function renderMenu(Request $request){        
        $dataArr = $request->all();       
        return view('backend.menu.render-menu', compact( 'dataArr' ));   
    }
    public function storeMenu(Request $request){
        $data = $request->all();
        Menu::where('menu_id', 1)->delete();
        if(!empty($data)){
            $i = 0;
            foreach($data['title'] as $k => $title){
                $i++;
                Menu::create([
                    'menu_id' => 1,
                    'title' => $title,
                    'url' => $data['url'][$k],
                    'slug' => $data['slug'][$k],
                    'type' => $data['type'][$k],
                    'object_id' => $data['object_id'][$k],
                    'status' => 1,
                    'title_attr' => $title,
                    'display_order' => $i
                ]);
            }
        }
        Session::flash('message', 'Cập nhật menu thành công.');

        return redirect()->route('menu.index');
    }
}
