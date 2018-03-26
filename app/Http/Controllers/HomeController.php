<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Cache, Session;
use App\Helpers\simple_html_dom;
use App\Helpers\JavascriptUnpacker;
use App\Models\DataVideo;
use App\Models\Customer;

class HomeController extends Controller
{
    public function index(Request $request){             
        $ax_url = $request->ax_url ? $request->ax_url : null;
        $code = '';
        if($ax_url){
            $this->validate($request,[
                'ax_url' => 'required|url'            
            ],
            [
                'ax_url.required' => 'Please enter URL.',            
                'ax_url.url' => 'URL is invalid.'
            ]);

            $rs = DataVideo::where('origin_url', $ax_url)->first();
            if(!$rs){
                
                if(Session::get('userId')){
                    $ax_url = $ax_url."-".Session::get('userId');
                    $detailCustomer = Customer::find(Session::get('userId'));
                    Cache::put("valid-".$detailCustomer->id, $detailCustomer->valid_from."-".$detailCustomer->valid_to, 1800);
                }
                Cache::put($code, $ax_url, 1800);
            }else{
                $code = $rs->code;
            }
        }

        return view('index', compact('ax_url', 'code'));
    }
    public function store(Request $request){             
        $ax_url = $request->ax_url ? $request->ax_url : null;
        $code = '';
        if($ax_url){
            $customer_id = Session::get('userId') ? Session::get('userId') : null;
            $this->validate($request,[
                'ax_url' => 'required|url'            
            ],
            [
                'ax_url.required' => 'Please enter URL.',            
                'ax_url.url' => 'URL is invalid.'
            ]);
            
            $rs = DataVideo::where('origin_url', $ax_url)->where('customer_id', $customer_id)->first();
            if(!$rs){
                
                if($customer_id){
                    $code = md5($ax_url."-".$customer_id);             
                    DataVideo::create(['origin_url' => $ax_url, 'code' => $code, 'customer_id' => $customer_id]);
                    $cache_url = $ax_url."-".$customer_id;
                    $detailCustomer = Customer::find($customer_id);
                    Cache::put("valid-".$customer_id, $detailCustomer->valid_from.":".$detailCustomer->valid_to, 1800);
                }else{
                    $cache_url = $ax_url;
                }
                Cache::put($code, $cache_url, 1800);
            }else{
                $code = $rs->code;               
            }
        }

        return view('index', compact('ax_url', 'code'));
    }
    public function play(Request $request){
        $code = $request->code;
        $origin_url = '';        
        $customer_id = null;
        if (Cache::has($code)){
            $origin_url = Cache::get($code);    
            $tmp = explode('-', $origin_url);
            if(isset($tmp[1])){
                $customer_id = $tmp[1];
                $origin_url = $tmp[0];
            }
        } else {
            $rs = DataVideo::where('code', $code)->first();
            $origin_url = $rs->origin_url;
            if($rs->customer_id > 0){
                $cache_url = $origin_url."-".$rs->customer_id;
            }else{
                $cache_url = $origin_url;
            }
            Cache::put($code, $cache_url, 1800);
        }
        
        if($customer_id){            
            if(Cache::has("valid-".$customer_id)){
                $valid_str = Cache::get("valid-".$customer_id);

                $tmpValid = explode(':', $valid_str);
                //dd($tmpValid);
                if(isset($tmpValid[1]) && $tmpValid[1] !=''){
                    if(date('Y-m-d')<= $tmpValid[1] && date('Y-m-d') >= $tmpValid[0]){
                        $license = 1;
                    }else{
                        $license = 0;
                    }
                }else{
                    $license = 0;        
                }
            }
        }else{
            $license = 0;
        }        
        $video_url = $poster_url = '';        
        if($origin_url != ''){
            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
            curl_setopt( $ch, CURLOPT_URL, $origin_url );
            curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $result = curl_exec($ch);            
            curl_close($ch);
            if( strpos($result, 'streamable')){
                $tmp = explode('"url": "', $result);               
                $tmp = explode('",', $tmp[1]);
                $video_url = "https:".$tmp[0];    
                $tmpPoster = explode('"thumbnail_url": "', $result);
                $tmp = explode('",', $tmpPoster[1]);
                $poster_url = "https:".$tmp[0];                                      
            }elseif( strpos($origin_url, 'fastplay.to')){                
                $crawler = new simple_html_dom();                
                $crawler->load($result); 
                $js = $crawler->find('script', 7)->innertext;
                $unpack = new JavascriptUnpacker;
                $tmpScript = $unpack->unpack($js);                
                                              
                $tmp = explode('{file:"', $tmpScript);
               
                if(isset($tmp[4])){
                    $tmp = explode('"', $tmp[4]);   
                    $video_url = $tmp[0];                 
                }elseif(isset($tmp[3])){
                    $tmp = explode('"', $tmp[3]);   
                    $video_url = $tmp[0];    
                }elseif(isset($tmp[2])){
                    $tmp = explode('"', $tmp[2]);   
                    $video_url = $tmp[0];    
                }elseif(isset($tmp[1])){
                    $tmp = explode('"', $tmp[1]);   
                    $video_url = $tmp[0];    
                }
                
                $tmpPoster = explode('image:"', $tmpScript);
              
                if(isset($tmpPoster[1])){
                    $tmp = explode('"', $tmpPoster[1]);   
                    $poster_url = $tmp[0];                 
                }
            }else{                
                $crawler = new simple_html_dom();                
                $crawler->load($result); 
                if($crawler->find('script', 4)){
                    $js = $crawler->find('script', 4)->innertext;
                    $unpack = new JavascriptUnpacker;
                    $tmpScript = $unpack->unpack($js);                               
                    $tmp = explode('{file:"', $tmpScript);
                    if(isset($tmp[1])){
                        $tmp = explode('"', $tmp[1]);   
                        $video_url = $tmp[0];                 
                    }
                    $tmpPoster = explode('image:"', $tmpScript);              
                    if(isset($tmpPoster[1])){
                        $tmp = explode('"', $tmpPoster[1]);   
                        $poster_url = $tmp[0];                 
                    }                
                }
                
            }            
            return view('play', compact('video_url', 'poster_url', 'license'));    
        }else{
            dd('Invalid code');
        }
        
    }    
}
