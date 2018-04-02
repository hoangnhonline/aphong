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
	<title>My link</title>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="robots" content="index,follow" />
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
						<img src="{{ URL::asset('public/assets/images/logo.png') }}" alt="Logo">
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
		<div class="container">
			<div style="background-color: #FFF;padding: 20px">
				<h3 style="margin-bottom: 20px;">MY LINKS ({{ $items->total() }})</h3>
				<div class="table-responsive">
					@if( $items->count() > 0)
					<table class="table table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>Original Link</th>
								<th>Encode Link</th>								
							</tr>
						</thead>
						<tbody>
							<?php $i = $limit*($page-1); ?>							
						@foreach($items as $item)
						<?php $i ++; 

						?>
					    <tr>
					        <th scope="row">{{ $i }}</th>
					        <td>{{ $item->origin_url }}</td>
					        <td>{{ route('play', $item->code)}}</td>
					    </tr>
					    @endforeach
					</tbody>
					</table>
					<div style="text-align: center;">
						{{ $items->links() }}
					</div>
					@endif
				</div>
			</div>
		</div>		
	</div><!-- /wrapper -->

	<script src="{{ URL::asset('public/assets/js/jquery.min.js') }}"></script>
    <!-- ===== JS Bootstrap ===== -->
    <script src="{{ URL::asset('public/assets/lib/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('public/assets/lib/wow/wow.min.js') }}"></script>
    <!-- Js Common -->
    <script src="{{ URL::asset('public/assets/js/common.js') }}"></script>

</body>
</html>