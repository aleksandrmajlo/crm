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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\Log;


class TaskService
{

    // при загрузке проверяем дубли
    // и присоединяем к заданию пользователя
    public static function DuplicateCheck($task_id)
    {
        $task = Task::find($task_id);
        $duplicates = Task::where('ip', $task->ip)
            ->whereNotIn('id', [$task_id])
            ->get();
        $admin_id = Auth::user()->id;
        if ($duplicates) {
            foreach ($duplicates as $duplicate) {

                if ($duplicate->status == 4) {
                    // если зафейлено
                    // само задание ставим статус в работе
                    $duplicate->status = 1;
                    $duplicate->user_id = null;
                    $duplicate->save();
                    // удаляем заказа
                    if ($duplicate->order) {
                        $duplicate_order = Order::find($duplicate->order->id);
                        $duplicate_order->delete();
                    }
                    Log::write(12, $duplicate->id, null, null, $admin_id);
                }

                //  статус в работе
                if (  $duplicate->status == 2) {
                    $origStatus = $duplicate->status;
                    $duplicate_order = Order::find($duplicate->order->id);
                    if (is_null($duplicate_order->parent_id)) {

                        // новый заказ
                        $order = new Order;
                        $order->task_id = $task_id;
                        $order->user_id = $duplicate->order->user_id;
                        $order->status = 1;
                        $order->type = 2;
                        $order->parent_id = $duplicate->order->id;
                        $order->created_at = new \DateTime();
                        $order->save();

                        // само задание ставим статус в работе
                        $task->status = 2;
                        $task->user_id = $duplicate->order->user_id;
                        $task->save();

                        // для выода сообщения
                        DB::table('tasklogs')->insert(
                            [
                                'task_id' => $task_id,
                                'order_id' => $order->id,
                                'user_id' => $duplicate->order->user_id,
                                'created_at' => Carbon::now()
                            ]
                        );
                        /*
                        if ($origStatus == 3) {
                            // лог к чему присоединяем
                            Log::write(13, $duplicate->id, $duplicate_order->id, $duplicate->order->user_id, $admin_id);
                            // что присоединили
                            Log::write(14, $task->id, $order->id, $duplicate->order->user_id, $admin_id, ['text' => 'Add to task ' . $duplicate->id]);

                        }
                        */
                        if ($origStatus == 2) {
                            // что присоединили
                            Log::write(15, $task->id, $order->id, $duplicate->order->user_id, $admin_id, ['text' => 'Add to task ' . $duplicate->id]);
                        }
                    }

                    /*
                    if ($origStatus == 3) {
                        //самому ордеру статус в работе
                        $duplicate_order->status = 1;
                        $duplicate_order->save();

                        // заданию статус в работе
                        $duplicate->status = 2;
                        $duplicate->save();
                    }
                    */


                }
                // если статус закончено
                // то опять статус в работе
                if($duplicate->status == 3){

                    $duplicate->status = 1;
                    $duplicate->user_id = null;
                    $duplicate->save();
                    // удаляем заказа
                    if ($duplicate->order) {
                        $duplicate_order = Order::find($duplicate->order->id);
                        $duplicate_order->delete();
                    }
                    Log::write(13, $duplicate->id, null,null, $admin_id);

                }
            }

        }
    }

    // если пользователь работник и имеет download 3 то эти задания отдаем ему
    public static function AddtaskThisUser($task,$admin_id){

        $order = new Order;
        $order->task_id = $task->id;
        $order->user_id = $admin_id;
        $order->status = 1;
        $order->type = 1;
        $order->save();
        $id = $order->id;

        Log::write(17, $task->id, $order->id,$admin_id,$admin_id);

        $task->status = 2;
        $task->user_id = $admin_id;
        $task->save();

    }

}
