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
    $IMAGE_HIDDEN=env("IMAGE_HIDDEN", false);
    $image=!empty($home->image) ? $home->image : null;
    if($IMAGE_HIDDEN){
        $image=false;
    }
    return view('home',[
        'text'=>$home->text,
        'image'=> $image ,
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

    });

    Route::group(['prefix' => 'ajaxuser', 'namespace' => 'Ajaxuser'], function(){
        // получить данные по пользователю
        Route::get('user', 'UserController@get');
        // получение заданий всех
        Route::get('tasks', 'UserController@tasks');
         // добавим задание для пользователя
        Route::get('addUserTask', 'UserController@addUserTask');
    });

    Route::group(['prefix' => 'order', 'namespace' => 'Order'], function(){
         // добавим задание для пользователя
        Route::post('addUserOrder', 'OrderController@add');
        // получить задания данного пользователя
        Route::get('thisuserorders', 'OrderController@orders');
        // сообщение об выполнении задания
        Route::post('setOrderCompletion', 'OrderController@setOrderCompletion');
    });


});

