<!DOCTYPE html>
<!--[if lt IE 7 ]><html dir="ltr" lang="vi-VN" class="no-js ie ie6 lte7 lte8 lte9"><![endif]-->
<!--[if IE 7 ]><html dir="ltr" lang="vi-VN" class="no-js ie ie7 lte7 lte8 lte9"><![endif]-->
<!--[if IE 8 ]><html dir="ltr" lang="vi-VN" class="no-js ie ie8 lte8 lte9"><![endif]-->
<!--[if IE 9 ]><html dir="ltr" lang="vi-VN" class="no-js ie ie9 lte9"><![endif]-->
<!--[if IE 10 ]><html dir="ltr" lang="vi-VN" class="no-js ie ie10 lte10"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="vn">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1"/>
	<title>Parse link</title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="robots" content="index,follow" />
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<!-- <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="icon" href="images/favicon.ico" type="image/x-icon"> -->
	<!-- ===== Style CSS ===== -->
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('public/assets/css/style.css') }}">

  	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
		<link href='css/animations-ie-fix.css' rel='stylesheet'>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<div class="wrapper">
		<header id="header" class="header">
			<div class="container">
				<h1 class="header-logo">
					<a href="{{ route('home')}}">
						<img src="{{ URL::asset('public/assets/images/logo.png') }}" width="96" alt="Logo">
					</a>
				</h1>
				<div class="btn-group block-btn-header">
					@if( !Session::get('login') )
					<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
						<button type="button" class="btn btn-primary login-by-facebook-popup">
							<i class="fa fa-facebook-square " aria-hidden="true"></i> Login
						</button>
					</a>
					@else
					<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
						<span><img src="{{ Session::get('avatar') ? Session::get('avatar') :  URL::asset('public/assets/img/icon.png') }}" class="user-image"></span> Hello, {{ Session::get('username') }}
					</a>
					<ul class="dropdown-menu animated fadeInRight">
						<li>
	                        <a href="{{ route('link') }}" title="My links">My links</a>
	                    </li> 	                    
	                    <li class="divider"></li>
	                    <li>
	                        <a href="{{ route('logout') }}" title="Logout">Logout</a>
	                    </li>
	                </ul>	            
					@endif
				</div>
			</div>
		</header><!-- /header -->
		<div id="box-table" class="box-table">
			<div class="box-table-cell">
				<div class="container">		

					<div id="form_container">    
						@if(Session::get('not-support'))
						<div class="alert alert-danger">
	                          <ul>
	                             
	                                  <li>Your link does not support, please try another link.</li>
	                             
	                          </ul>
	                      </div>
						@endif
						@if (count($errors) > 0)
	                      <div class="alert alert-danger">
	                          <ul>
	                              @foreach ($errors->all() as $error)
	                                  <li>{{ $error }}</li>
	                              @endforeach
	                          </ul>
	                      </div>
	                    @endif 		 
	                    <div class="clearfix"></div>               
                        <form action="{{ route('home') }}" method="POST" class="form_container">
                            {{ csrf_field() }}

                            <input name="ax_url" type="text" class="shorten-input" placeholder="Paste a link" value="{{ $ax_url }}" autocomplete="off" autocorrect="off" autocapitalize="off">
                            <button id="shorten_btn" type="submit" class="btn shorten-button">Parse Link</button>
                        </form>
                        
                    </div>
                    @if($ax_url && $code)
                    <div id="result">
                    	<ul class="nav nav-tabs">
						  <li class="active"><a data-toggle="tab" href="#home">Direct Link</a></li>
						  <li><a data-toggle="tab" href="#menu1">Embed</a></li>						  
						</ul>

						<div class="tab-content">
						  <div id="home" class="tab-pane fade in active" style="margin-top: 10px;">
						    <div class="input-group">
	                          <input style="background-color: #FFF !important; box-shadow:none !important;margin-right: 5px !important" type="text" id="linkresult" value="{{ route('play', [$code]) }}" class="form-control" readonly="readonly">
	                          <span class="input-group-btn">
	                            <button type="button" onclick="copyLink()" class="btn btn-info">COPY</button>
	                          </span>
	                        </div>
						  </div>
						  <div id="menu1" class="tab-pane fade" style="margin-top: 10px;">
						  	<div class="input-group">
						    <textarea class="form-control" id="embedcode"><iframe width="560" height="315" src="{{ route('play', [$code]) }}" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen readonly="readonly"></iframe></textarea>
						    <span class="input-group-btn">
	                            <button type="button" onclick="copyLink2()" class="btn btn-info">COPY</button>
	                          </span>
	                      </div>
						  </div>
						  
						</div>
                        
                    </div>
                    @endif
					
				</div>	
			</div>
		</div>		
	</div><!-- /wrapper -->
	<input type="hidden" id="route-ajax-login-fb" value="https://toolshot.net/social-auth/facebook/fb-login">
	<input type="hidden" id="fb-app-id" value="{{ env('FACEBOOK_APP_ID') }}">    
	<script src="{{ URL::asset('public/assets/js/jquery.min.js') }}"></script>
    <!-- ===== JS Bootstrap ===== -->
    <script src="{{ URL::asset('public/assets/lib/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('public/assets/lib/wow/wow.min.js') }}"></script>
    <!-- Js Common -->
    <script src="{{ URL::asset('public/assets/js/common.js') }}"></script>
	<script>
		$('#box-table').css({
			position: 'absolute',
			top: 0,
		});
		function copyLink2() {
		  /* Get the text field */
		  var copyText = document.getElementById("embedcode");

		  /* Select the text field */
		  copyText.select();

		  /* Copy the text inside the text field */
		  document.execCommand("Copy");

		  /* Alert the copied text */
		  alert("Copied the code: " + copyText.value);
		}
	</script>

</body>
</html>