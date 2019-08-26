<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Task;
use App\User;
use App\Order;
use App\Services\OrderService;

class OrderController extends Controller
{
    // добавить заказ
    public function add(Request $request)
    {
        $ids = [];
        $task_id = $request->id;
        $user_id = Auth::user()->id;
        $user_weight = Auth::user()->weight;

        $task = Task::findOrFail($task_id);
        // проверяем или не привышен лимит
        $count = OrderService::getWeigthOrderUser($user_id, $task->weight, $user_weight);
        // добавить
        if ($count) {
            // проверка или заказ не в работе
            if ($task->status !== 1) {
                return response()->json(['engaged' => trans('order.order_is_work')], 200);
            }
            DB::table('orders')->insert(
                array(
                    'task_id' => $task->id,
                    'user_id' => $user_id,
                    'status' => 1,
                    'type' => 1,
                    'created_at' => new \DateTime()
                )

            );
            $id = DB::getPdo()->lastInsertId();;
            // вставляем заказ
            $task->status = 2;
            $task->user_id = $user_id;
            $task->order_id = $id;
            $task->save();
            $ids[] = $task_id;
            //получить похожие по ип
            $task_others = Task::where('ip', $task->ip)->where('id', '!=', $task->id)
                ->get();
            if (count($task_others) > 0) {
                foreach ($task_others as $task_otner) {

                    $task_otner->status = 2;
                    $task_otner->user_id = $user_id;
                    $task_otner->order_id = $id;
                    $task_otner->save();

                    DB::table('orders')->insert(
                        array(
                            'task_id' => $task_otner->id,
                            'user_id' => $user_id,
                            'status' => 1,
                            'type' => 2,
                            'parent_id' => $id,
                            'created_at' => new \DateTime()
                        )
                    );

                    $ids[] = $task_otner->id;
                }
            }
            return response()->json(
                [
                    'limit_used' => OrderService::getLimitUser(),
                    'success' => trans('order.success'),
                    'id' => $ids
                ], 200);
        } else {
            // cообщить что лимит перевыщын
            return response()->json(['notorder' => trans('order.notorder'),], 200);
        }
    }


    // получить заказы пользователя
    public function orders(Request $request)
    {
        $user_id = Auth::user()->id;
        $orders = OrderService::getOrderActive($user_id);
        $history = OrderService::getOrderHistory($user_id);
        return response()->json([
            'orders' => $orders,
            'failed_status' => config('status_coments.failed'),
            'history' => $history
        ], 200);
    }

    // отчет по заказу
    public function orderSend(Request $request)
    {
        $order_id = $request->order;
        $comments = $request->comments;
        $status = $request->status;

        $order = Order::find($order_id);
        $order->status = $status;
        $order->save();

        $task = Task::find($order->task_id);
        $task->status = $status;
        $task->comments = $comments;
        $task->save();

        return response()->json(['success' => true], 200);


    }

    //выполнение задания
    public function setOrderCompletion(Request $request)
    {
        $status = $request->input('status');
        $id = $request->input('id');
        $task_id = $request->input('task_id');
        if ($status == 4) {

            $comment = serialize(
                [
                    'comment' => $request->input('comment'),
                    'select' => $request->input('select'),
                ]
            );
            DB::table('orders')
                ->where('id', $id)
                ->update([
                    'status' => $status,
                    'comment' => $comment,
                    'updated_at' => new \DateTime()
                ]);




            $task = Task::find($task_id);
            $task->status = $status;
            $task->save();

        }
        else {

            if ($request->has('data_sub_options')) {

                $data_sub_options = $request->input('data_sub_options');
                $comment = serialize(
                    [
                        'comment' => $request->input('succes_textarea_all'),
                        'data_sub_options' => $data_sub_options,
                    ]
                );

            }else{
                $comment = serialize(
                    [
                        'serial_number' => $request->input('serial_number'),
                        'comment' => $request->input('comment'),
                    ]
                );
            }

            $task = Task::find($task_id);
            $task->status = $status;
            $task->save();

            DB::table('orders')
                ->where('id', $id)
                ->update([
                    'comment' => $comment,
                    'status' => $status,
                    'updated_at' => new \DateTime()
                ]);
        }

        // обработать заказы который шли добавочно
        $sub_orders=DB::table('orders')
            ->where('parent_id', $id)
            ->get();
        if ($sub_orders){
            foreach ($sub_orders as $sub_order ){
                DB::table('orders')
                    ->where('id', $sub_order->id)
                    ->update([
                        'status' => $status,
                        'updated_at' => new \DateTime()
                    ]);
                $task = Task::find($sub_order->task_id);
                $task->status = $status;
                $task->save();
            }
        }

        return response()->json([
            'success' => true,
            'id' => $id
        ], 200);
    }

}
