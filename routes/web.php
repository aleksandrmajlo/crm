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
    if (Auth::user()->role==1){
        $data=[
            'title'=>'Dashboard1',
            'meta_title'=>'Dashboard',
            'with_sidebar'=>false,
            'with_content'=>'12',
            'statistics'=>App\Services\DashboardService::getStatistics()
        ];
        return view('dashboard',$data);
    }
    else{
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
    }
});
Route::get('/noaccess', 'NoaccessController@index')->name('noaccess');
Auth::routes();
Route::group(['middleware' => 'access'], function () {

    // загрузка заданий списка для  administrator
    Route::get('/import', 'TasksettingController@index')->name('import');

    // поиск на сайте
    Route::get('/search', 'SearchController@index')->name('search');

    // поиск на сайте по дате
    Route::get('/searchdate', 'SearchController@searchdate')->name('searchdate');

    // вывод списка для administrator moderator
    Route::get('/taskslist', 'TaskController@index')->name('taskslist');

    // вывод списка для administrator со cтатусом несделано
    Route::get('/orderLog', 'OrderController@orderLog')->name('orderLog');

    Route::post('/ordercomment', 'OrderController@orderCommentAdmin')->name('orderCommentAdmin');

    // вывод списка для   executor(работника)
    Route::get('/taskslistuser', 'TasklistuserController@index')->name('taskslistuser');

    // страница заказов executor My order
    Route::get('/mytask', 'PersonalController@index')->name('mytask');

    // редактировать заказ
    Route::get('/editOrder', 'PersonalController@editOrder')->name('editOrder');


    Route::get('/contact', 'ContactController@index')->name('contact');
    Route::get('/help', 'HelpController@index')->name('help');

    // почистить серийники
    Route::get('/clearTask', 'CleartaskController@index')->name('clear');

    // вывод записей
    Route::get('/posts', 'PostController@index')->name('posts');
    Route::get('/post/{slug}', 'PostController@post')->name('post');

    Route::group(['prefix' => 'ajax', 'namespace' => 'Ajax'], function(){
        // загрузка заданий из .txt
        Route::post('upload', 'UploadController@store');

        //сохранение загруженых  заданий из .txt и отедактированных
        Route::post('publish', 'UploadController@publish');

        // получить все задания для админа и модернатора
        Route::get('get_tasks', 'TaskAdminController@get');

        // сохранить отредактированные заданий (доступно для админа и модернатора  за последние 2 дня)
        Route::post('save', 'TaskAdminController@save');
        // сохранить одиночное (доступно для админа и модернатора  за последние 2 дня)
        Route::post('SaveOneWeigth', 'TaskAdminController@SaveOneWeigth');

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
        Route::get('thisUserOrders', 'OrderController@orders');
        // сообщение об выполнении задания
        Route::post('setOrderCompletion', 'OrderController@setOrderCompletion');
        // получить заданиe данного пользователя
        Route::post('thisUserOrder', 'OrderController@thisUserOrder');
        // обновить иформацию по заказу
        Route::post('UpdateUserOrder', 'OrderController@UpdateUserOrder');
        // admin устанавливает статут свободно для заказа failed
        Route::post('FailedFree', 'AdminorderController@FailedFree');
        // admin устанавливает принудительно заказ для пользователя
        Route::post('SetUserOrder', 'AdminorderController@SetUserOrder');
    });

    Route::group(['prefix' => 'dashbord', 'namespace' => 'Dashbord'], function(){
         // получить отчет в админке по дню
        Route::post('dateGetDashbord', 'DashbordController@get');
        // получить всех пользователей
        Route::post('usersGetDashbord', 'DashbordController@getUsers');
        // получить  по конкретному пользователей
        Route::post('userGetDashbord', 'DashbordController@getUser');
    });

    Route::group(['prefix' => 'search', 'namespace' => 'Search'], function(){
         // получить отчет в админке по дню
        Route::post('ip', 'SearchController@ip');
    });


});

