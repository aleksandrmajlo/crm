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
use App\Services\Log;

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

        $log_data['falied']=[
            'failedstatus'=>$data['comment'],
            'comment' => $data['comment'],
        ];


        // записуем в лог
        Log::write(6,
            $order->task_id,
            $order->id,
            $order->user_id,
            null,
            [
                'comment' => $data['comment'],
                'select' => $data['select'],
            ]
        );

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

        $log_data['done']=[];
        $log_data['done']['serials']=[];
        $log_data['done']['commentall']= "";

        if($serials){
            foreach ($serials as $item){
                $serial = new Serial;
                $serial->serial = $item['serial'];
                $serial->link = $item['link'];
                $serial->text = $item['text'];
                $serial->task_id = $task_id;
                $serial->order_id = $order_id;
                $serial->save();

                $log_data['done']['serials'][]=[
                    'serial'=>$item['serial'],
                    'link' =>$item['link'],
                    'text' =>$item['text']
                ];

            }
        }
        $task = Task::find($task_id);
        $task->status = $status;
        $task->save();

        // записуем в лог
        Log::write(7,
            $task_id,
            $order->id,
            $order->user_id,
            null,
            $log_data
        );

        // обработать заказы который шли добавочно
        $sub_orders = DB::table('orders')
            ->where('parent_id', $order_id)
            ->get();
        if ($sub_orders) {
            $log_data['parenttask'] = 'Add in addition to task:' . $task_id;
            foreach ($sub_orders as $sub_order) {
                DB::table('orders')
                    ->where('id', $sub_order->id)
                    ->update([
                        'status' => $status,
                    ]);
                $task = Task::find($sub_order->task_id);
                $task->status = $status;
                $task->save();
                // записуем в лог
                Log::write(7,
                    $sub_order->task_id,
                    $sub_order->id,
                    $order->user_id,
                    null,
                    $log_data
                );


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

        $log_data['done']=[];
        $log_data['done']['serials']=[];
        $log_data['done']['commentall']= $data['comment'];

        if($serials){
            foreach ($serials as $item){
                $serial = new Serial;
                $serial->serial = $item['serial'];
                $serial->link = $item['link'];
                $serial->text = $item['text'];
                $serial->task_id = $task_id;
                $serial->order_id = $order_id;
                $serial->save();

                $log_data['done']['serials'][]=[
                    'serial'=>$item['serial'],
                    'link' =>$item['link'],
                    'text' =>$item['text']
                ];

            }
        }
        // записуем в лог
        Log::write(4,
            $task_id,
            $order->id,
            $order->user_id,
            null,
            $log_data
        );

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

        // записуем в лог
        Log::write(5,
            $task_id,
            $order->id,
            $order->user_id,
            null,
            [
                'comment' => $data['comment'],
                'select' => $data['select'],
            ]
        );

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

                $suborder = Order::find($sub_order->id);
                // записуем в лог
                Log::write(5,
                    $task_id,
                    $suborder->id,
                    $suborder->user_id,
                    null,
                    [
                        'comment' => $data['comment'],
                        'select' => $data['select'],
                    ]
                );
            }
        }


    }
}