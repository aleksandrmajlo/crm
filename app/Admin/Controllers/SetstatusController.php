<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 14.08.2019
 * Time: 19:38
 */

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Order;
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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\MessageBag;

use App\Task;
use App\Observers\TaskObserver;


class SetstatusController extends Controller
{

    use HasResourceActions;
    protected $title = 'Backup end Clear table work ';

    public function index(Content $content)
    {
        $content->header('Backup end Clear table work');
        $dir = 'becup/mysql';
        $becups = Storage::allFiles($dir);
        $content->view('admin.setStatus', [
            'becups' => $becups
        ]);
        return $content;
    }

    public function update(Request $request)
    {
        $res = Artisan::call('backup:run');
        $arrIds = [];

        if ($request->has('date') && $request->date) {

            $tasks = DB::table('tasks')->where('status', '!=', '2')
                ->where('created_at', '<', $request->date . ' 00:00:00')
                ->get();
            foreach ($tasks as $task) {
                $arrIds[] = $task->id;
            }

        } else {
            $tasks = DB::table('tasks')->where('status', '!=', '2')->get();
            foreach ($tasks as $task) {
                $arrIds[] = $task->id;
            }
        }

        $parenArrs = [];
        if (!empty($arrIds)) {

            ///*
            foreach ($arrIds as $arrId) {
                $orders = Order::where('task_id', $arrId)->get();
                if ($orders) {
                    foreach ($orders as $order) {
                        if ($order->parent_id) {
                            $parenArrs[] = $order->parent_id;
                        }
                    }
                }
            }
            //*/

           // /*

            DB::table('tasks')->whereIn('id', $arrIds)->delete();
            DB::table('tasklogs')->whereIn('task_id', $arrIds)->delete();

            DB::table('orders')->whereIn('task_id', $arrIds)->delete();
            DB::table('orders')->whereIn('id', $parenArrs)->delete();

            DB::table('orderlogs')->whereIn('task_id', $arrIds)->delete();
            DB::table('orderlogs')->whereIn('order_id', $parenArrs)->delete();

            DB::table('serials')->whereIn('task_id', $arrIds)->delete();
            DB::table('serials')->whereIn('order_id', $parenArrs)->delete();

            DB::table('admincomments')->whereIn('task_id', $arrIds)->delete();
            DB::table('admincomments')->whereIn('order_id', $parenArrs)->delete();

            DB::table('notes')->whereIn('task_id', $arrIds)->delete();
            DB::table('notes')->whereIn('order_id', $parenArrs)->delete();

            //*/

        }

        /*
        if($request->has('date')&&$request->date){
            DB::table('tasks')->where('created_at', '<',  $request->date.' 00:00:00')->delete();
            DB::table('tasklogs')->where('created_at', '<',  $request->date.' 00:00:00')->delete();
            DB::table('orders')->where('created_at', '<',  $request->date.' 00:00:00')->delete();
            DB::table('orderlogs')->where('created_at', '<',  $request->date.' 00:00:00')->delete();
            DB::table('serials')->where('created_at', '<',  $request->date.' 00:00:00')->delete();
            DB::table('admincomments')->where('created_at', '<',  $request->date.' 00:00:00')->delete();
            DB::table('notes')->where('created_at', '<',  $request->date.' 00:00:00')->delete();
        }else{
            DB::table('tasks')->delete();
            DB::table('tasks')->delete();
            DB::table('tasklogs')->delete();
            DB::table('orders')->delete();
            DB::table('orderlogs')->delete();
            DB::table('serials')->delete();
            DB::table('admincomments')->delete();
            DB::table('notes')->delete();
        }
        */

//        dump($arrIds);
//        dump($parenArrs);
//        dd();

        return Redirect::back()->with('mess', 'Updated!');
    }
}
