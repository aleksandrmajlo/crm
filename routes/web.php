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

    // загрузка заданий списка для  administrator
    Route::get('/import', 'TasksettingController@index')->name('import');

    // вывод списка для administrator moderator
    Route::get('/taskslist', 'TaskController@index')->name('taskslist');

    // вывод списка для   executor(работника)
    Route::get('/taskslistuser', 'TasklistuserController@index')->name('taskslistuser');
    // страница заказов executor(работника)
    Route::get('/mytask', 'PersonalController@index')->name('mytask');

    Route::get('/contact', 'ContactController@index')->name('contact');
    Route::get('/help', 'HelpController@index')->name('help');

    // вывод записей
    Route::get('/posts', 'PostController@index')->name('posts');
    Route::get('/post/{slug}', 'PostController@post')->name('post');

    Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax'], function(){
        // загрузка заданий из .txt
        Route::post('upload', 'UploadController@store');
        //сохранение загруженых  зазаданий из .txt и отедактированных
        Route::post('uploadsave', 'UploadController@uploadsave');

        // получить все задания
        Route::get('get_tasks', 'TaskController@get');

        // сохранить отредактированные заданий
        Route::post('saveread', 'TaskController@saveread');

        //получить скрытую инфу
        Route::post('task', 'TaskController@one');
        // добавить заказ
        Route::post('order', 'OrderController@add');
        // получить заказы пользователя
        Route::post('orders', 'OrderController@orders');
        // отправка
        Route::post('orderSend', 'OrderController@orderSend');
    });

    Route::group(['prefix' => 'ajaxuser', 'namespace' => 'Ajaxuser'], function(){
        // получить данные по пользователю
        Route::get('user', 'UserController@get');
        // получение заданий
        Route::get('tasks', 'UserController@tasks');
    });


});

