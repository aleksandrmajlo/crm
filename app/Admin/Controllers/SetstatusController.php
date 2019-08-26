<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 14.08.2019
 * Time: 19:38
 */

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\HasResourceActions;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Form;
use Encore\Admin\Widgets\Tab;
use Encore\Admin\Widgets\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\MessageBag;

use App\Task;
use App\Observers\TaskObserver;



class SetstatusController  extends Controller
{

    use HasResourceActions;
    protected $title = 'Set Task status Free ';

    public function index(Content $content)
    {

        $content->header('Set Task status Free');
        $content->view('admin.setStatus', [

        ]);
        return $content;

    }

    public function update(){
        DB::table('tasks')->update(
            [
            'order_id'=>null,
            'user_id'=>null,
            'status' => 1
            ]
        );
        return Redirect::back()->with('mess', 'Updated!');
    }
}