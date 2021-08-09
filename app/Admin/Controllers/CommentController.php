<?php

/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 27.08.2019
 * Time: 13:17
 */

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\SiteSetting;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Form;
use Encore\Admin\Widgets\Tab;
use Encore\Admin\Widgets\Table;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\MessageBag;

use \App\Services\CommentService;

use View;


class CommentController extends Controller
{

    use HasResourceActions;
    protected $title = 'Comment';


    public function index(Content $content)
    {
        return $content
            ->header('Comment')
            ->description('Comment')
            ->row(function (Row $row) {
                $request = Request::class;
                $text = "";
                if (isset($_GET) && isset($_GET['id'])) {
                    $data = CommentService::get($_GET['id']);
                    $view = View::make('admin.comment', [
                        'data' => $data,
                        'failed_status' => config('status_coments.failed')
                    ]);
                    $text = $view->render();
                }
                $row->column(12, $text);
            });
    }
}
