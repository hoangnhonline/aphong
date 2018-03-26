<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
	<meta charset="utf-8">
	<title>Play</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="{{ URL::asset('public/assets/css/play.css') }}" type="text/css">
</head>
<body>
    <div class="player-container"> 
		<div id="video"></div>
	</div>
	<script type="text/javascript" src="{{ URL::asset('public/assets/plugins/jwplayer/jwplayer.js') }}" ></script>	
	<script>
	var purl='{{ $video_url }}';
	var pimg='{{ $poster_url }}';
	@if($license == 0)
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('d 2=g(\'4\');2.5({6:\'7+8==\',9:{},a:\'b%\',c:\'\',1:e,f:3,h:{1:"../i/j/k/l.m",n:\'o-p\',q:\'0\',r:"s://t.u"}});',31,31,'|file|player|pimg|video|setup|key|tjQq7CNG7oULq6qy|s5IUmOtg0JusfzoTjBSTQ|cast|width|100|type|var|purl|image|jwplayer|logo|public|assets|images|download|svg|position|bottom|right|margin|link|http|google|com'.split('|'),0,{}))
	@else
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('8 0=2(\'3\');0.4({5:\'6+7==\',1:{},9:\'a%\',b:\'\',c:d,e:f});',16,16,'player|cast|jwplayer|video|setup|key|tjQq7CNG7oULq6qy|s5IUmOtg0JusfzoTjBSTQ|var|width|100|type|file|purl|image|pimg'.split('|'),0,{}))
	@endif
	eval(function(p,a,c,k,e,r){e=function(c){return c.toString(a)};if(!''.replace(/^/,String)){while(c--)r[e(c)]=k[c]||e(c);k=[function(e){return r[e]}];e=function(){return'\\w+'};c=1};while(c--)if(k[c])p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c]);return p}('7 1(){c 2=3,d=a,e=d.6,g=d.8(\'9\')[0],4=2.b||e.5||g.5;f.1(\'h%\',4)}1();3.i=1;',19,19,'|resize|w|window|y|clientHeight|documentElement|function|getElementsByTagName|body|document|innerHeight|var|||player||100|onresize'.split('|'),0,{}))
    </script>
</body>
</html>