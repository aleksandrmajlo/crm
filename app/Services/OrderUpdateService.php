<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 22.10.2019
 * Time: 21:51
 */

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Order;
use App\Serial;
use App\Task;

class OrderUpdateService
{
    // обновление order со стасусом 4
    public static function updateFailed($data)
    {
        $order_id = $data['order_id'];
        $comment = serialize(
            [
                'comment' => $data['comment'],
                'select' => $data['select'],
            ]
        );
        $order = Order::find($order_id);
        $order->comment = $comment;
        $order->save();

    }

    public static function changeFailed($data)
    {

        $status=3;
        $order_id = $data['order_id'];
        $order = Order::find($order_id);
        $order->status = $status;
        $order->comment = "";
        $order->save();

        $task_id=$order->task_id;
        $serials=$data['serials'];
        if($serials){
            foreach ($serials as $item){
                $serial = new Serial;
                $serial->serial = $item['serial'];
                $serial->link = $item['link'];
                $serial->text = $item['text'];
                $serial->task_id = $task_id;
                $serial->order_id = $order_id;
                $serial->save();
            }
        }
        $task = Task::find($task_id);
        $task->status = $status;
        $task->save();

        // обработать заказы который шли добавочно
        $sub_orders = DB::table('orders')
            ->where('parent_id', $order_id)
            ->get();
        if ($sub_orders) {
            foreach ($sub_orders as $sub_order) {
                DB::table('orders')
                    ->where('id', $sub_order->id)
                    ->update([
                        'status' => $status,
                    ]);
                $task = Task::find($sub_order->task_id);
                $task->status = $status;
                $task->save();
            }
        }

    }

    public static function updateDone($data){
        $order_id = $data['order_id'];
        $order = Order::find($order_id);

        $order->comment = $data['comment'];
        $order->save();

        Serial::where('order_id',$order_id)->delete();
        $task_id=$order->task_id;
        $serials=$data['serials'];
        if($serials){
            foreach ($serials as $item){
                $serial = new Serial;
                $serial->serial = $item['serial'];
                $serial->link = $item['link'];
                $serial->text = $item['text'];
                $serial->task_id = $task_id;
                $serial->order_id = $order_id;
                $serial->save();
            }
        }

    }

    public static function changeDone($data){

        $status=4;
        $order_id = $data['order_id'];
        $comment = serialize(
            [
                'comment' => $data['comment'],
                'select' => $data['select'],
            ]
        );

        $order = Order::find($order_id);
        $task_id=$order->task_id;
        $order->status = $status;
        $order->comment = $comment;
        $order->save();

        Serial::where('order_id',$order_id)->delete();

        $task = Task::find($task_id);
        $task->status = $status;
        $task->save();

        // обработать заказы который шли добавочно
        $sub_orders = DB::table('orders')
            ->where('parent_id', $order_id)
            ->get();
        if ($sub_orders) {
            foreach ($sub_orders as $sub_order) {
                DB::table('orders')
                    ->where('id', $sub_order->id)
                    ->update([
                        'status' => $status,
                    ]);
                $task = Task::find($sub_order->task_id);
                $task->status = $status;
                $task->save();
            }
        }
    }
}