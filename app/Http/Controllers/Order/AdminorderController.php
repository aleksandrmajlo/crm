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



    // устанавливаем принудительно заказ админом для пользователя
    public function  ChangeOrder(Request $request){

        $status=$request->status;
        $task_id=$request->task_id;
        $task = Task::find($task_id);

        if($status=='2'){
            $user_id=$request->user_id;
            OrderService::add($task,$user_id);
        }else{
            // статус свободно
            if($task->status!==1){
                OrderService::setFreeStatus($task);
            }
        }

        return response()->json(
            [
                'success' =>true,
            ], 200);


    }
}