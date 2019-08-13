<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    if (Auth::guest()) {
        return redirect('login');
    }
    $home=\App\Infopage::where('alias','home')->first();
    return view('home',[
        'text'=>$home->text,
        'image'=>!empty($home->image) ? $home->image : null ,
        'title'=>$home->h1,
        'meta_title'=>$home->meta_title,
        'meta_description'=>$home->meta_description,
    ]);
});
Route::get('/noaccess', 'NoaccessController@index')->name('noaccess');
Auth::routes();
Route::group(['middleware' => 'access'], function () {
    Route::get('/task', 'TaskController@index')->name('task');
    Route::get('/mytask', 'PersonalController@index')->name('mytask');

    Route::get('/contact', 'ContactController@index')->name('contact');
    Route::get('/help', 'HelpController@index')->name('help');

    Route::get('/posts', 'PostController@index')->name('posts');
    Route::get('/post/{slug}', 'PostController@post')->name('post');

    Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax'], function(){
        // получить все задания
        Route::post('tasks', 'TaskController@get');
        //получить скрытую инфу
        Route::post('task', 'TaskController@one');
        // добавить заказ
        Route::post('order', 'OrderController@add');
        // получить заказы пользователя
        Route::post('orders', 'OrderController@orders');
        // отправка
        Route::post('orderSend', 'OrderController@orderSend');
    });
});

