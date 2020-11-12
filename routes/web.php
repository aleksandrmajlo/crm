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
Route::get('/noaccess', 'NoaccessController@index')->name('noaccess');
Auth::routes();
Route::group(['middleware' => 'access'], function () {
    Route::get('/','HomeController@index');
    // загрузка заданий списка для  administrator
    Route::get('/import', 'TasksettingController@index')->name('import');
    // поиск на сайте
    Route::get('/search', 'SearchController@index')->name('search');
    Route::get('/searchIP', 'SearchController@searchIP')->name('searchIP');
    // поиск на сайте по дате
    Route::get('/searchdate', 'SearchController@searchdate')->name('searchdate');

    // вывод списка для administrator истории общей
    Route::get('/orderLog', 'OrderController@orderLog')->name('orderLog');

    // поиск по ID в логе
    Route::get('/orderLogID', 'OrderController@orderLogID')->name('orderLogID');

    // вывод списка для administrator истории общей
    Route::post('/orderLog', 'OrderController@orderLogAjax')->name('orderLogAjax');

    // вывод списка для   executor(работника)
    Route::get('/taskslistuser', 'TasklistuserController@index')->name('taskslistuser');

    // вывод списка для   executor(работника) cвободные
    Route::get('/taskslistuserfree', 'TasklistuserController@taskslistuserfree')->name('taskslistuserfree');

    // редактировать заказ
    Route::get('/editOrder', 'PersonalController@editOrder')->name('editOrder');

    Route::get('/contact', 'ContactController@index')->name('contact');
    Route::get('/help', 'HelpController@index')->name('help');

    // почистить серийники
    Route::get('/clearTask', 'CleartaskController@index')->name('clear');

    // вывод записей
    Route::get('/posts', 'PostController@index')->name('posts');
    Route::get('/post/{slug}', 'PostController@post')->name('post');

    // комментарии
    Route::group(['prefix' => 'comment', 'namespace' => 'Comment'],function (){
        Route::post('/add', 'CommentController@add');
        Route::post('/get', 'CommentController@get');
    });

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
         // добавить задание для пользователя
        Route::get('addUserTask', 'UserController@addUserTask');

        //установить коммент просмотреным
        Route::post('CommentViewed', 'UserController@CommentViewed');

        // получение заданий всех
        Route::post('messange', 'UserController@messange');
        Route::post('messangeReadStaus', 'UserController@messangeReadStaus');
        // получение ленты комментов
        Route::get('commentsFeed', 'UserController@commentsFeed');
        // export ордеров
        Route::get('ordersExportUsers', 'UserController@ordersExportUsers');
    });

    Route::group(['prefix' => 'order', 'namespace' => 'Order'], function(){
         // добавим задание для пользователя
        Route::post('addUserOrder', 'OrderController@add');
        // получить задания данного пользователя
        Route::get('thisUserOrders', 'OrderController@thisUserOrders');
        // получить историю ордеров пользователя,
        Route::get('thisHistoryOrders', 'OrderController@thisHistoryOrders');

        // сообщение об выполнении задания
        Route::post('setOrderCompletion', 'OrderController@setOrderCompletion');

        // установка массово  значения failed
        Route::post('FieldArray', 'OrderController@FieldArray');

        // получить заданиe данного пользователя
        Route::post('thisUserOrder', 'OrderController@thisUserOrder');
        // обновить иформацию по заказу
        Route::post('UpdateUserOrder', 'OrderController@UpdateUserOrder');
        // admin устанавливает принудительно заказ для пользователя или меняет статус
        Route::post('ChangeOrder', 'AdminorderController@ChangeOrder');

        // пометки
        Route::post('addNote', 'NoteController@addNote');
        // для пользователя список
        Route::get('getNoteOrder', 'NoteController@getNoteOrder');
        // для админа список
        Route::get('getNoteOrderAdmin', 'NoteController@getNoteOrderAdmin');
        // отказатся от заказа
        Route::post('refuseOrder','OrderController@refuseOrder');

    });

    Route::group(['prefix' => 'dashbord', 'namespace' => 'Dashbord'], function(){
         // получить отчет в админке по дню
        Route::post('dateGetDashbord', 'DashbordController@get');
        // получить всех пользователей
        Route::post('usersGetDashbord', 'DashbordController@getUsers');

        // получить  по конкретному пользователей
        Route::get('userGetDashbord', 'DashbordController@getUser');

    });

    Route::group(['prefix' => 'search', 'namespace' => 'Search'], function(){
         // получить отчет в админке по дню
        Route::post('ip', 'SearchController@ip');
    });


});
// админы
Route::group(['middleware'=>'isadmin'],function (){
    // вывод списка для administrator moderator
    Route::get('/taskslistread', 'TaskController@index')->name('taskslistread');
    Route::get('/taskslist', 'TaskController@listing')->name('taskslist');

});
// работники

Route::group(['middleware'=>'isworker'],function (){

    Route::get('/commentfeed','Comment\CommentController@GommentFeed')->name('commentfeed');
    // страница заказов executor My order
    Route::get('/mytask', 'PersonalController@index')->name('myTask');
    // страница История
    Route::get('/myhistory', 'PersonalController@History')->name('myHistory');
});

