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
use Illuminate\Support\Carbon;
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
            $now=Carbon::now();

            DB::table('tasks')->whereIn('id', $arrIds)->update(['deleted_at' => $now]);
            DB::table('tasklogs')->whereIn('task_id', $arrIds)->update(['deleted_at' => $now]);

            DB::table('orders')->whereIn('task_id', $arrIds)->update(['deleted_at' => $now]);
            DB::table('orders')->whereIn('id', $parenArrs)->update(['deleted_at' => $now]);

            DB::table('orderlogs')->whereIn('task_id', $arrIds)->update(['deleted_at' => $now]);
            DB::table('orderlogs')->whereIn('order_id', $parenArrs)->update(['deleted_at' => $now]);

            DB::table('serials')->whereIn('task_id', $arrIds)->update(['deleted_at' => $now]);
            DB::table('serials')->whereIn('order_id', $parenArrs)->update(['deleted_at' => $now]);

            DB::table('admincomments')->whereIn('task_id', $arrIds)->update(['deleted_at' => $now]);
            DB::table('admincomments')->whereIn('order_id', $parenArrs)->update(['deleted_at' => $now]);

            DB::table('notes')->whereIn('task_id', $arrIds)->update(['deleted_at' => $now]);
            DB::table('notes')->whereIn('order_id', $parenArrs)->update(['deleted_at' => $now]);

        }
        return Redirect::back()->with('mess', 'Updated!');
    }
}
