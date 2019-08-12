<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\Task;
use App\User;
use App\Order;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function add(Request $request)
    {
        $task_id = $request->task_id;
        $user_id = Auth::user()->id;
        // проверяем или не больше 10 заказов у работники
        $count=OrderService::getCountOrderUser($user_id);
        // добавить
        if($count<11){
            $this_task=Task::find($task_id);
            DB::table('orders')->insert(
                array(
                    'task_id' => $task_id,
                    'user_id' => $user_id,
                    'status'=>1,
                    'type'=>1,
                    'created_at'=>new \DateTime()
                )
            );
            $this_task->status=2;
            $this_task->user_id=$user_id;
            $this_task->save();
            $ids=[$task_id];
            $other_tasks=Task::where('ip',$this_task->ip)
                ->where('id', '!=', $this_task->id)->get();
            if($other_tasks->isNotEmpty()){
                foreach ($other_tasks as $other_task){
                    DB::table('orders')->insert(
                        array(
                            'task_id' => $other_task->id,
                            'user_id' => $user_id,
                            'status'=>1,
                            'type'=>2,
                            'created_at'=>new \DateTime()
                        )
                    );
                    $other_task_=Task::find($other_task->id);
                    $other_task_->status=2;
                    $other_task_->user_id=$user_id;
                    $other_task_->save();

                    $ids[]=$other_task->id;
                }
            }
            return response()->json(
                [
                'success' => trans('order.success'),
                'ids'=>$ids
                ], 200);
        }else{
            // cообщить что в работе уже 10 заказов
            return response()->json(['notorder' => trans('order.notorder'),], 200);
        }
    }


    // получить заказы пользователя
    public function orders(Request $request){
        $orders=OrderService::getOrderActive();
        $history=OrderService::getOrderHistory();
        return response()->json([
            'orders' => $orders,
            'history' =>$history
        ], 200);
    }

    // отчет по заказу
    public  function orderSend(Request $request){
        $order_id=$request->order;
        $comments=$request->comments;
        $status=$request->status;

        $order=Order::find($order_id);
        $order->status=$status;
        $order->save();

        $task=Task::find($order->task_id);
        $task->status=$status;
        $task->comments=$comments;
        $task->save();

        return response()->json(['success' => true], 200);


    }

}
