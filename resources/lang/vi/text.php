<?php
$arr = DB::table('text')->whereRaw('1')->get();
$arrReturn = [];
foreach($arr as $k => $v){
    $arrReturn[$v->text_key] = $v->text_vi;
}
return $arrReturn;