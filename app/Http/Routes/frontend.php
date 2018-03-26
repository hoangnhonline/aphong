<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
Route::get('trau/', ['as' => 'trau', 'uses' => 'CrawlerController@trau']);
Route::get('/test', function() {
    return view('frontend.email.thanks');
});

Route::group(['prefix' => 'social-auth'], function () {
    Route::group(['prefix' => 'facebook'], function () {
        Route::get('redirect/', ['as' => 'fb-auth', 'uses' => 'SocialAuthController@redirect']);
        Route::get('callback/', ['as' => 'fb-callback', 'uses' => 'SocialAuthController@callback']);
        Route::post('fb-login', ['as' => 'ajax-login-by-fb', 'uses' => 'SocialAuthController@fbLogin']);
    });

    Route::group(['prefix' => 'google'], function () {
        Route::get('redirect/', ['as' => 'gg-auth', 'uses' => 'SocialAuthController@googleRedirect']);
        Route::get('callback/', ['as' => 'gg-callback', 'uses' => 'SocialAuthController@googleCallback']);
    });

});

Route::group(['prefix' => 'authentication'], function () {
    Route::post('check_login', ['as' => 'auth-login', 'uses' => 'AuthenticationController@checkLogin']);
    Route::post('login_ajax', ['as' =>  'auth-login-ajax', 'uses' => 'AuthenticationController@checkLoginAjax']);
    Route::get('/user-logout', ['as' => 'logout', 'uses' => 'AuthenticationController@logout']);
});
Route::get('/', ['uses' => 'HomeController@index', 'as' => 'home']);
Route::post('/', ['uses' => 'HomeController@store', 'as' => 'store']);
Route::get('/play/{code}', ['uses' => 'HomeController@play', 'as' => 'play']);
Route::group(['namespace' => 'Frontend'], function()
{
    
    Route::post('/rating', ['as' => 'rating', 'uses' => 'HomeController@insertRating']);    
    Route::get('/search-product', ['as' => 'search-product', 'uses' => 'HomeController@searchProduct']);    
    Route::post('/send-contact', ['as' => 'send-contact', 'uses' => 'ContactController@store']);
    Route::post('/send-bao-gia', ['as' => 'send-thi-cong', 'uses' => 'ContactController@storeThiCong']);
    Route::post('/send-thiet-ke', ['as' => 'send-thiet-ke', 'uses' => 'ContactController@storeThietKe']);
    Route::get('tag/{slug}', ['as' => 'tag', 'uses' => 'DetailController@tagDetail']);
    Route::get('tin-tuc/{slug}', ['as' => 'news-list', 'uses' => 'NewsController@newsList']);
    Route::get('/tin-tuc/{slug}-p{id}.html', ['as' => 'news-detail', 'uses' => 'NewsController@newsDetail']);
    Route::get('/dich-vu/{slug}-s{id}.html', ['as' => 'services-detail', 'uses' => 'NewsController@newsDetail']);

    Route::get('{slug}-{id}.html', ['as' => 'product', 'uses' => 'DetailController@index']);
    
    Route::group(['prefix' => 'gio-hang'], function () {
        Route::get('/', ['as' => 'cart', 'uses' => 'CartController@index']);
        Route::get('thong-tin-nhan-hang', ['as' => 'address-info', 'uses' => 'CartController@addressInfo']);
        Route::get('thong-tin-don-hang', ['as' => 'order-info', 'uses' => 'CartController@orderInfo']);
        Route::get('empty-cart', ['as' => 'empty-cart', 'uses' => 'CartController@deleteAll']);
        Route::get('get-branch', ['as' => 'get-branch', 'uses' => 'CartController@getBranch']);
        Route::get('phuong-thuc-thanh-toan', ['as' => 'payment-method', 'uses' => 'CartController@paymentInfo']);
        Route::post('store-address', ['as' => 'store-address', 'uses' => 'CartController@storeAddress']);
        Route::get('short-cart', ['as' => 'short-cart', 'uses' => 'CartController@shortCart']);
        Route::any('shipping-step-1', ['as' => 'shipping-step-1', 'uses' => 'CartController@shippingStep1']);
        Route::get('shipping-step-2', ['as' => 'shipping-step-2', 'uses' => 'CartController@shippingStep2']);
        Route::get('shipping-step-3', ['as' => 'shipping-step-3', 'uses' => 'CartController@shippingStep3']);
        Route::post('update-product', ['as' => 'update-product', 'uses' => 'CartController@update']);
        Route::get('add-product', ['as' => 'add-product', 'uses' => 'CartController@addProduct']);
        Route::get('mua-hang-thanh-cong', ['as' => 'success', 'uses' => 'CartController@success']);
        Route::post('save-order', ['as' => 'payment', 'uses' => 'CartController@saveOrder']);        
    });
    Route::group(['prefix' => 'tai-khoan'], function () {
        Route::get('don-hang-cua-toi', ['as' => 'order-history', 'uses' => 'OrderController@history']);

        Route::get('thong-bao-cua-toi', ['as' => 'notification', 'uses' => 'CustomerController@notification']);
        Route::get('thong-tin-tai-khoan', ['as' => 'account-info', 'uses' => 'CustomerController@accountInfo']);
        Route::group(['prefix' => 'so-dia-chi'], function () {
            Route::get('/', ['as' => 'account-address', 'uses' => 'CustomerController@address']);
            Route::match(['get', 'post'], '/them-moi', ['as' => 'account-address-create', 'uses' => 'CustomerController@createAddress']);
            Route::match(['get', 'post'], '/chinh-sua/{id}', ['as' => 'account-address-edit', 'uses' => 'CustomerController@editAddress']);
            Route::post('/xoa/{id}', ['as' => 'account-address-destroy', 'uses' => 'CustomerController@destroyAddress']);
        });
        Route::get('doi-mat-khau', ['as' => 'change-password', 'uses' => 'CustomerController@changePassword']);
        Route::post('cap-nhat', ['as' => 'update-customer', 'uses' => 'CustomerController@update']);
        Route::post('save-new-password', ['as' => 'save-new-password', 'uses' => 'CustomerController@saveNewPassword']);
        Route::get('/chi-tiet-don-hang/{order_id}', ['as' => 'order-detail', 'uses' => 'OrderController@detail']);
        Route::post('/huy-don-hang', ['as' => 'order-cancel', 'uses' => 'OrderController@huy']);
        Route::post('/forget-password', ['as' => 'forget-password', 'uses' => 'CustomerController@forgetPassword']);
        Route::get('/gui-lai-mat-khau', ['as' => 'gui-lai-mk', 'uses' => 'CustomerController@guilaiMatkhau']);
        Route::get('/reset-password/{key}', ['as' => 'reset-password', 'uses' => 'CustomerController@resetPassword']);
        Route::post('save-reset-password', ['as' => 'save-reset-password', 'uses' => 'CustomerController@saveResetPassword']);
    });
    Route::get('/dang-tin-ky-gui.html', ['as' => 'ky-gui', 'uses' => 'DetailController@kygui']);
    Route::get('/dang-tin-thanh-cong.html', ['as' => 'ky-gui-thanh-cong', 'uses' => 'DetailController@kyguiSuccess']);    
    Route::post('/post-ky-gui', ['as' => 'post-ky-gui', 'uses' => 'DetailController@postKygui']);    

    Route::post('/dang-ki-newsletter', ['as' => 'register.newsletter', 'uses' => 'HomeController@registerNews']);    
    
    Route::get('/tim-kiem.html', ['as' => 'search', 'uses' => 'HomeController@search']);

    Route::get('lien-he.html', ['as' => 'contact', 'uses' => 'HomeController@contact']);
    Route::get('dich-vu.html', ['as' => 'services', 'uses' => 'HomeController@services']);

    Route::get('{slug}.html', ['as' => 'pages', 'uses' => 'HomeController@pages']);    
    Route::get('{slugCateParent}', ['as' => 'cate-parent', 'uses' => 'CateController@cateParent']);    
    Route::get('{slugCateParent}/{slugCateChild}', ['as' => 'cate', 'uses' => 'CateController@cateChild']);
    Route::get('{slugCateParent}/{slugCateChild}/{slugGrand}', ['as' => 'grand', 'uses' => 'CateController@grand']);

    

    
   
    Route::get('/cap-nhat-thong-tin', ['as' => 'cap-nhat-thong-tin', 'uses' => 'CartController@updateUserInformation']);
    Route::post('/get-district', ['as' => 'get-district', 'uses' => 'DistrictController@getDistrict']);
    Route::post('/get-ward', ['as' => 'get-ward', 'uses' => 'WardController@getWard']);
    
    Route::post('/customer/register', ['as' => 'register-customer', 'uses' => 'CustomerController@register']);
    Route::post('/customer/register-ajax', ['as' => 'register-customer-ajax', 'uses' => 'CustomerController@registerAjax']);
    Route::post('/customer/checkemail', ['as' => 'checkemail-customer', 'uses' => 'CustomerController@isEmailExist']);  
});

