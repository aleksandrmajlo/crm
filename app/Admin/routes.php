<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('admin.home');


    $router->resource('tasks', TaskController::class);

    $router->resource('users', UserController::class);

    $router->resource('orders', OrderController::class);

    $router->get('usersEdit', 'UserController@editTask');
    $router->post('usersEdit', 'UserController@editTask');
    $router->put('usersEdit', 'UserController@editTask');

//    $router->get('site-settings', 'SettingController@index')->name('siteSettings');
//    $router->post('site-settings', 'SettingController@update');

    $router->get('advert', 'AdvertController@index');
    $router->post('advert', 'AdvertController@update');


    $router->get('infopages', 'InfopageController@index');
    $router->post('infopages', 'InfopageController@update');



    $router->resource('posts', PostController::class);


    $router->get('history', 'HistoryController@index');


    // module
    $router->get('flag', 'FlagController@index');
    $router->post('flag', 'FlagController@update');

    $router->get('setStatus', 'SetstatusController@index');
    $router->post('setStatus', 'SetstatusController@update');

    // module end

    Route::group(['prefix' => 'ajax'], function(Router $router){
        $router->post('userhistory', 'UserController@userhistory');
    });



});
