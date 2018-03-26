<?php 
$str_page = isset($page) && $page > 1 ? " trang $page" : "";
?>
@section('title'){!! $seo['title'].$str_page !!}@endsection
@section('site_description'){!! $seo['description'].$str_page !!}@endsection
@section('site_keywords'){!! $seo['keywords'].$str_page !!}@endsection
@section('site_name'){!! $settingArr['site_name'] !!}@endsection
@section('favicon'){!! Helper::showImage($settingArr['favicon']) !!}@endsection
@section('logo'){!! Helper::showImage($settingArr['logo']) !!}@endsection

