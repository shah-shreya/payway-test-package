<?php

Route::group(['namespace' => 'App\Http\controllers\payway'], function(){
    Route::get('payway_status','PaywayController@getPaywayStatus');
    Route::get('paywithpayway','PaywayController@payway');
});
