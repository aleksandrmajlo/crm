<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 05.11.2019
 * Time: 14:26
 */

namespace App\Http\Controllers\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Log;
use App\Services\OrderService;
use App\Order;
use App\Task;

class AdminorderController  extends Controller
{
    // установить заказ свободным
    public function FailedFree(Request $request){
        $order_id=$request->order_id;

        $order=Order::find($order_id);
        $order->comment="";
        $order->status=1;
        $order->type=1;
        $order->created_at = new \DateTime();
        $order->save();

        $task=Task::find($order->task_id);
        $task->status = 1;
        $task->user_id = null;
        $task->save();

        Log::write($order,1);
        return response()->json(
            [
                'success' =>true,
            ], 200);

    }

    // устанавливаем принудительно заказ админом для пользователя
    public function  SetUserOrder(Request $request){
        $task_id=$request->task_id;
        $user_id=$request->user_id;
        $task = Task::find($task_id);
        OrderService::add($task,$user_id);
        return response()->json(
            [
                'success' =>true,
            ], 200);

    }
}