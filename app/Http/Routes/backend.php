<?php
// Authentication routes...
Route::get('backend/login', ['as' => 'backend.login-form', 'uses' => 'Backend\UserController@loginForm']);
Route::post('backend/login', ['as' => 'backend.check-login', 'uses' => 'Backend\UserController@checkLogin']);
Route::get('backend/logout', ['as' => 'backend.logout', 'uses' => 'Backend\UserController@logout']);
Route::group(['namespace' => 'Backend', 'prefix' => 'backend', 'middleware' => 'isAdmin'], function()
{   
    Route::group(['prefix' => 'customer'], function () {
        Route::get('/', ['as' => 'customer.index', 'uses' => 'CustomerController@index']);
        Route::post('/store', ['as' => 'customer.store', 'uses' => 'CustomerController@store']);
        Route::get('{id}/edit',   ['as' => 'customer.edit', 'uses' => 'CustomerController@edit']);
        Route::get('/address/{id}',   ['as' => 'customer.address', 'uses' => 'CustomerController@address']);
        Route::get('/edit-address/{address_id}',   ['as' => 'customer.edit-address', 'uses' => 'CustomerController@editAddress']);
        Route::get('/export',   ['as' => 'customer.export', 'uses' => 'CustomerController@download']);
        Route::post('/update', ['as' => 'customer.update', 'uses' => 'CustomerController@update']);
        Route::post('/update-address', ['as' => 'customer.update-address', 'uses' => 'CustomerController@updateAddress']);
        Route::get('{id}/destroy', ['as' => 'customer.destroy', 'uses' => 'CustomerController@destroy']);
    });
   
    Route::group(['prefix' => 'account'], function () {
        Route::get('/', ['as' => 'account.index', 'uses' => 'AccountController@index']);
        Route::get('/change-password', ['as' => 'account.change-pass', 'uses' => 'AccountController@changePass']);
        Route::post('/store-password', ['as' => 'account.store-pass', 'uses' => 'AccountController@storeNewPass']);
        Route::get('/update-status/{status}/{id}', ['as' => 'account.update-status', 'uses' => 'AccountController@updateStatus']);
        Route::get('/create', ['as' => 'account.create', 'uses' => 'AccountController@create']);
        Route::post('/store', ['as' => 'account.store', 'uses' => 'AccountController@store']);
        Route::get('{id}/edit',   ['as' => 'account.edit', 'uses' => 'AccountController@edit']);
        Route::post('/update', ['as' => 'account.update', 'uses' => 'AccountController@update']);
        Route::get('{id}/destroy', ['as' => 'account.destroy', 'uses' => 'AccountController@destroy']);
    });
        
});