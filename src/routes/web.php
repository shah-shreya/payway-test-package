<?php

Route::group(['namespace' => 'App\Http\Controllers\payway'], function(){
    Route::post('payway_status','PaywayController@getPaywayStatus');
    Route::get('paywithpayway','PaywayController@payway');
});
