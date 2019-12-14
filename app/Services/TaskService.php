<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 07.12.2019
 * Time: 14:05
 */

namespace App\Services;
use App\Order;
use App\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\Log;


class TaskService
{

    // при загрузке проверяем дубли
    // и присоединяем к заданию пользователя
    public static function DuplicateCheck($task_id){
         $task=Task::find($task_id);
         $duplicate = Task::where('ip', $task->ip)
            ->whereNotIn('id', [$task_id])
            ->first();
         if($duplicate){
                 if($duplicate->order){

                     $duplicate_order=Order::find($duplicate->order->id);

                     $order = new Order;
                     $order->task_id = $task_id;
                     $order->user_id = $duplicate->order->user_id;
                     $order->status = 1;
                     $order->type = 2;
                     $order->parent_id = $duplicate->order->id;
                     $order->created_at = new \DateTime();
                     $order->save();

                     DB::table('tasklogs')->insert(
                         [
                             'task_id' => $task_id,
                             'order_id' => $order->id,
                             'user_id' => $duplicate->order->user_id,
                             'created_at' => Carbon::now()
                         ]
                     );

                     // само задание ставим статус в работе
                     $task->status=2;
                     $task->user_id=$duplicate->order->user_id;
                     $task->save();

                     // если статус в работе ********************************
                     if($duplicate->order->status==1){

                     }
                     // если статус в работе end ********************************

                     // если статус завершено ********************************
                     if($duplicate->order->status==3||$duplicate->order->status==4){


                         $duplicate_order->status = 1;
                         $duplicate_order->save();

                         // заданию статус в работе
                         $duplicate->status=2;
                         $duplicate->save();

                     }
                     // если статус завершено end ****************************
                     Log::write($duplicate_order, 2, $duplicate->order->user_id);

                 }
         }
    }
}