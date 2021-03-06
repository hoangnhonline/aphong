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
    
    public function curl($url) {
        $ch = @curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $head[] = "Connection: keep-alive";
        $head[] = "Keep-Alive: 300";
        $head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
        $head[] = "Accept-Language: en-us,en;q=0.5";
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
        $page = curl_exec($ch);
        curl_close($ch);
        return $page;
    }
    public function getFacebook($link){
        if(substr($link, -1) != '/' && is_numeric(substr($link, -1))){
            $link = $link.'/';
        }
        preg_match('/https:\/\/www.facebook.com\/(.*)\/videos\/(.*)\/(.*)\/(.*)/U', $link, $id); // link dạng https://www.facebook.com/userName/videos/vb.IDuser/IDvideo/?type=2&theater
        if(isset($id[4])){
            $idVideo = $id[3];
        }else{
            preg_match('/https:\/\/www.facebook.com\/(.*)\/videos\/(.*)\/(.*)/U', $link, $id); // link dạng https://www.facebook.com/userName/videos/IDvideo
            if(isset($id[3])){
                $idVideo = $id[2];
            }else{
                preg_match('/https:\/\/www.facebook.com\/video\.php\?v\=(.*)/', $link, $id); // link dạng https://www.facebook.com/video.php?v=IDvideo
                $idVideo = $id[1];
                $idVideo = substr($idVideo, 0, -1);
            }
        }
        $embed = 'https://www.facebook.com/video/embed?video_id='.$idVideo; // đưa link về dạng embed
        $get = $this->curl($embed);
 
        $HD = explode('"hd_src":', $get);
        $HD = explode(',', $HD[1]);       

        $HD = str_replace('\/', '/', $HD[0]);   
        $HD = str_replace('"', '', $HD);     
        //Link SD
        $SD = explode('"sd_src":', $get);

        $SD = explode(',', $SD[1]);
               
        $SD = $SD[0];
        $SD = str_replace('\/', '/', $SD);
        $SD = str_replace('"', '', $SD);
        
        if($HD != 'null'){
            $linkDownload['HD'] = $HD; // link download HD
        }
        if($SD){
            $linkDownload['SD'] = $SD; // link download SD
        }
        $imageVideo = '';
        $tmp = explode("background-image: url(", $get);
        if(isset($tmp[1])){
            $tmp = explode(');"', $tmp[1]);
            if($tmp[0]){
                $imageVideo = html_entity_decode($tmp[0], ENT_QUOTES);
                $imageVideo = str_replace('\3a ', ":", $imageVideo);
                $imageVideo = str_replace('\3d ', "=", $imageVideo);
                $imageVideo = str_replace('\26 ', "&", $imageVideo);
                $imageVideo = str_replace("'", "", $imageVideo);
            }
        }        
        $linkVideo = array_values($linkDownload);
        $return['linkVideo'] = $linkVideo[0]; // link video có độ phân giải lớn nhất
        $return['imageVideo'] = $imageVideo; // ảnh thumb của video
        $return['linkDownload'] = $linkDownload; // link download video
        return $return;
    }
    public function facebook($url) {
        $poster_url = $video_url = '';
        $useragent = 'Mozilla/5.0 (Linux; U; Android 2.3.3; de-de; HTC Desire Build/GRI40) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $source = curl_exec($ch);
        curl_close($ch);        
        $download = explode('/video_redirect/?src=', $source);
        if(!isset($download[1])){
             echo "Your link does not support, please try another link.";die;
        }
        $download = explode('&amp', $download[1]);
        $download = rawurldecode($download[0]);

        $tmp = explode('property="og:image" content="', $source);
        if(isset($tmp[1])){
            $tmp2 = explode('" />', $tmp[1]);
            $poster_url = str_replace("&amp;", "&", $tmp2[0]);
        }        
        return ['video_url' => $download, 'poster_url' => $poster_url];
    }
    public function youtube($url){
        preg_match("/^(?:http(?:s)?:\/\/)?(?:www\.)?(?:m\.)?(?:youtu\.be\/|youtube\.com\/(?:(?:watch)?\?(?:.*   &)?v(?:i)?=|(?:embed|v|vi|user)\/))([^\?&\"'>]+)/", $url, $matches);    
        if(isset($matches[1])){
            $id = $matches[1];
        }else{
            echo "Your link does not support, please try another link.";die;
        }
        parse_str(file_get_contents('http://www.youtube.com/get_video_info?video_id='.$id), $video_data);        
        $streams = $video_data['url_encoded_fmt_stream_map'];
        $streams = explode(',',$streams);
        $counter = 1;        
        foreach ($streams as $streamdata) {
            parse_str($streamdata,$streamdata);            
            foreach ($streamdata as $key => $value) {
                
                if ($key == "url") {
                    return $video_url = urldecode($value);                                        
                }
            }
        }
        return $video_url;
    }
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
    public function link(Request $request){
        $keyword = $request->keyword ? trim($request->keyword) : null;
        $user_id = Session::get('userId');
        if(!$user_id){
            return redirect()->route('home');
        }
        $page = $request->page ? $request->page : 1;
        $limit = 100;
        $query = DataVideo::where('customer_id', $user_id);
        if($keyword){
            $query->where('origin_url', $keyword);
        }
        $items = $query->orderBy('id', 'desc')->paginate($limit);
        return view('link', compact('items', 'page', 'limit', 'keyword'));

    }
    public function store(Request $request){             
        $ax_url = $request->ax_url ? $request->ax_url : null;
        $code = '';
        if($ax_url){
            if( strpos($ax_url, 'xvideos') == 0
                && strpos($ax_url, 'xnxx.com') == 0
                && strpos($ax_url, 'tnaflix') == 0
                && strpos($ax_url, 'facebook') == 0 
                && strpos($ax_url, 'streamable.com') == 0 
                && strpos($ax_url, 'nodefiles.com') == 0                

        ){
                Session::put('not-support', 1);
            return redirect()->route('home');
            }else{
                Session::forget('not-support');
            }
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
                    $code = md5($ax_url);    
                    $cache_url = $ax_url;
                }
                Cache::put($code, $cache_url, 1800);
                //dd($ax_url, $code);
            }else{
                $code = $rs->code;               
            }
        }

        return view('index', compact('ax_url', 'code'));
    }
 
    public function play(Request $request){
       
        $license = 0;
        $code = $request->code;
        $origin_url = '';        
        $customer_id = null;
        if (Cache::has($code)){
            $origin_url = Cache::get($code);    
            $tmp = explode('-', $origin_url);
            if( end($tmp) > 0){
                $customer_id = end($tmp);               
                $origin_url = str_replace("-".$customer_id, "", $origin_url);
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
        
        if($customer_id > 0){            
            if(Cache::has("valid-".$customer_id)){
                $valid_str = Cache::get("valid-".$customer_id);

                $tmpValid = explode(':', $valid_str);
                
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
            if(strpos($origin_url, 'facebook.com') > 0){
                $tmp = $this->getFacebook($origin_url);
                if($tmp){
                    $video_url = $tmp['linkVideo'];
                    $poster_url = $tmp['imageVideo'];    
                }
                
            }elseif(strpos($origin_url, 'youtube.com') > 0){                
                $video_url = $this->youtube($origin_url);
            }else{
                $ch = curl_init();
                curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
                curl_setopt( $ch, CURLOPT_URL, $origin_url );
                curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
                if(strpos($origin_url, 'xvideos') > 0 || strpos($origin_url, 'xnxx.com') > 0){
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3B48b Safari/419.3');    
                }
                //curl_setopt($ch, CURLOPT_REFERER, "https://www.xnxx.com");
                //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $result = curl_exec($ch);            
                
                curl_close($ch);
                $htmlGet = new simple_html_dom();                
                $htmlGet->load($result);  

                if(strpos($origin_url, 'xvideos') > 0){ 
                  
                    $tmp1 = explode("setVideoUrlHigh('", $result);
                    
                    if(isset($tmp1[1])){
                        $tmp2 = explode("');", $tmp1[1]);         
                    }else{                        
                        $tmp1 = explode("setVideoUrlLow('", $result);
                        if(isset($tmp1[1])){                                
                            $tmp2 = explode("');", $tmp1[1]);         
                        }else{
                            echo "Your link does not support, please try another link.";die;
                        }
                    }  

                    $video_url = $tmp2[0];

                    $tmpThumb = explode("setThumbUrl('", $result);
                    if(isset($tmpThumb[1])){
                        $tmpThum2 = explode("');", $tmpThumb[1]);         
                        $poster_url = $tmpThum2[0];
                    }
                    
                }elseif(strpos($origin_url, 'hihi.com') > 0 ){
                    if($htmlGet->find('#player source', 0)){
                        $video_url = $htmlGet->find('#player source', 0)->src;               
                    }
                    if($htmlGet->find('#player-openload', 0)){
                        $video_url = $htmlGet->find('#player-openload', 0)->src; 
                        $is_hihi = 1;
                        $isXvideo = 0;
                    }
                }elseif(strpos($origin_url, 'javbuz.com') > 0 ){
                    $video_url = $htmlGet->find('source[data-res]', 0)->src;
                    
                }elseif(strpos($origin_url, 'xnxx.com') > 0 ){                        
                    
                    $tmp1 = explode("setVideoUrlHigh('", $result);
                    
                    if(isset($tmp1[1])){
                        $tmp2 = explode("');", $tmp1[1]);                   
                    }else{
                        
                        $tmp1 = explode("setVideoUrlLow('", $result);     
                        if(isset($tmp1[1])){
                            $tmp2 = explode("');", $tmp1[1]); 
                        }else{
                            echo "Your link does not support, please try another link.";die;
                        }        
                    }      
                    $video_url = $tmp2[0];

                    $tmpThumb = explode("setThumbUrl('", $result);
                    if(isset($tmpThumb[1])){
                        $tmpThum2 = explode("');", $tmpThumb[1]);         
                        $poster_url = $tmpThum2[0];
                    }
                    
                }elseif(strpos($origin_url, 'redtube.com') > 0){
                    $video_url = $htmlGet->find('source', 0)->src;
                }elseif(strpos($origin_url, 'youporn.com') > 0){
                    $video_url = $htmlGet->find('.downloadOption', 0)->find('a', 0)->href;
                }elseif(strpos($origin_url, 'tnaflix.com') > 0 ){
                    $video_url = $htmlGet->find('meta[itemprop=contentUrl]', 0)->content;
                    $poster_url = str_replace("w300", "w800", $htmlGet->find('meta[itemprop=thumbnailUrl]', 0)->content);
                }elseif( strpos($result, 'streamable')){
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
            }
                     
            return view('play', compact('video_url', 'poster_url', 'license'));    
        }else{
            dd('Invalid code');
        }
        
    }    
}
