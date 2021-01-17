<?php

namespace App\Http\Controllers\Order;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Task;
use App\User;
use App\Order;
use App\Serial;
use App\Services\OrderService;
use App\Services\OrderUpdateService;
use App\Services\Log;
use App\Orderlog;

class OrderController extends Controller
{
    // добавить заказ
    public function add(Request $request)
    {
        $ids = [];
        $task_id = $request->id;
        $user_id = Auth::user()->id;
        $user_weight = Auth::user()->weight;
        $task = Task::find($task_id);
        // проверяем или не привышен лимит
        $count = OrderService::getWeigthOrderUser($user_id, $task->weight, $user_weight);
        // добавить
        if ($count) {
            // проверка или заказ не в работе
            if ($task->status !== 1) {
                return response()->json(['engaged' => trans('order.order_is_work')], 200);
            }

            // проверка может order для задания реализован
            if (!is_null($task->order)) {
                $order = Order::find($task->order->id);
                $order->user_id = $user_id;
                $order->status = 1;
                $order->type = 1;
                $order->created_at = new \DateTime();
                $order->save();
                $id = $task->order->id;
            }
            else {
                $order = new Order;
                $order->task_id = $task->id;
                $order->user_id = $user_id;
                $order->status = 1;
                $order->type = 1;
                $order->save();
                $id = $order->id;
            }
            // записуем в лог
            Log::write(1, $task_id, $order->id, $user_id, null, null);
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

                    if (!is_null($task_otner->order)) {
                        $order = Order::find($task_otner->order->id);
                        $order->user_id = $user_id;
                        $order->status = 1;
                        $order->type = 2;
                        $order->parent_id = $id;
                        $order->created_at = new \DateTime();
                        $order->save();
                        $id_other = $order->id;

                    } else {

                        $order = new Order;
                        $order->task_id = $task_otner->id;
                        $order->user_id = $user_id;
                        $order->status = 1;
                        $order->type = 2;
                        $order->parent_id = $id;
                        $order->save();
                        $id_other = $order->id;

                    }

                    $task_otner->status = 2;
                    $task_otner->user_id = $user_id;
                    $task_otner->order_id = $id_other;
                    $task_otner->save();
                    $ids[] = $task_otner->id;

                    // записуем в лог
                    Log::write(1, $task_otner->id, $order->id, $user_id, null, ['text' => 'Add in addition to task:' . $task_id]);

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
    public function thisUserOrders(Request $request)
    {
        $user_id = Auth::user()->id;
        $orders = OrderService::getOrderActive($user_id);
        return response()->json([
            'orders' => $orders,
            'failed_status' => config('status_coments.failed'),
        ], 200);
    }

    // получить заказы пользователя
    public function thisHistoryOrders(Request $request)
    {
        $user_id = Auth::user()->id;
        $history = OrderService::getOrderHistory($user_id);
        return response()->json([
            'history' => $history,
            'failed_status' => config('status_coments.failed'),
            'status' => config('adm_settings.LogStatus')
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
        $log_data = [];
        if ($status == 4) {
            $comment = serialize(
                [
                    'comment' => $request->input('comment'),
                    'select' => $request->input('select'),
                ]
            );
            $order = Order::find($id);
            $order->status = $status;
            $order->comment = $comment;
            $order->updated_at = new \DateTime();
            $order->save();

            $task = Task::find($task_id);
            $task->status = 4;
            $task->save();
            // записуем в лог
            Log::write(3, $task_id, $order->id, $order->user_id, null,
                [
                    'comment' => $request->input('comment'),
                    'select' => $request->input('select'),
                ]
            );
            // обработать заказы который шли добавочно
            $sub_orders = DB::table('orders')
                ->where('parent_id', $id)
                ->get();
            if ($sub_orders) {
                foreach ($sub_orders as $sub_order) {
                    $suborder = Order::find($sub_order->id);
                    $suborder->status = 4;
                    $suborder->save();
                    // ставим статус свободно
                    $sub_task = Task::find($sub_order->task_id);
                    $sub_task->status = $status;
                    $sub_task->save();
                    // записуем в лог
                    Log::write(3, $sub_order->task_id, $sub_order->id, $order->user_id, null,
                        [
                            'parenttask' => 'Add in addition to task:' . $task_id,
                            'comment' => $request->input('comment'),
                            'select' => $request->input('select'),
                        ]
                    );

                }
            }

        }
        else {

            $task = Task::find($task_id);
            $task->status = $status;
            $task->save();
            $user_id=Auth::user()->id;


            //******************* Запись серийных номеров *******************************************
            // если IP не имеет детей то пишем тоже в Serial
            $log_data['done'] = [];
            $log_data['done']['serials'] = [];

            if ($request->has('serial_number') && !empty($request->input('serial_number'))) {

                $serial = new Serial;
                $serial->serial = $request->input('serial_number');
                $serial->link = $request->input('link');
                $serial->text = $request->input('succes_textarea_all');
                $serial->task_id = $task_id;
                $serial->order_id = $id;

                $serial->user_id = $user_id;
                $serial->port=$task->port;
                $serial->ip=$task->ip;

                $serial->save();

                $log_data['done']['serials'][] = [
                    'serial' => $request->input('serial_number'),
                    'link' => $request->input('link'),
                    'text' => $request->input('succes_textarea_all')
                ];

            }
            $data_sub_options = $request->input('data_sub_options');
            if ($data_sub_options) {
                foreach ($data_sub_options as $item) {
                    if (!empty($item['serialinp'])) {

                        $serial = new Serial;
                        $serial->serial = $item['serialinp'];
                        $serial->link = $item['link'];
                        $serial->text = $item['text'];
                        $serial->task_id = $task_id;
                        $serial->order_id = $id;

                        $serial->user_id = $user_id;
                        $serial->port=$task->port;
                        $serial->ip=$task->ip;

                        $serial->save();

                        $log_data['done']['serials'][] = [
                            'serial' => $item['serialinp'],
                            'link' => $item['link'],
                            'text' => $item['text']
                        ];

                    }
                }
            }
            //******************* Запись серийных номеров end *******************************************



            $comment_all_With_Serials = false;
            if ($request->has('comment_all_With_Serials') && !empty($request->input('comment_all_With_Serials'))) {
                $comment_all_With_Serials = $request->input('comment_all_With_Serials');
                $log_data['done']['commentall'] = $request->input('comment_all_With_Serials');
            }

            $order = Order::find($id);
            $order->status = $status;
            $order->comment = $comment_all_With_Serials;
            $order->save();


            // записуем в лог
            Log::write(2, $task_id, $order->id, $order->user_id, null,
                $log_data
            );

            // обработать заказы который шли добавочно
            $sub_orders = DB::table('orders')
                ->where('parent_id', $id)
                ->get();
            if ($sub_orders) {
                $log_data['parenttask'] = 'Add in addition to task:' . $task_id;
                foreach ($sub_orders as $sub_order) {

                    $order = Order::find($sub_order->id);
                    $order->status = $status;
                    $order->save();

                    $task = Task::find($sub_order->task_id);
                    $task->status = $status;
                    $task->save();

                    // записуем в лог
                    Log::write(2,
                        $task->id,
                        $order->id,
                        $order->user_id,
                        null,
                        $log_data
                    );
                }
            }

        }
        return response()->json([
            'success' => true,
            'id' => $id
        ], 200);
    }

    // отказаться от задания
    public function refuseOrder(Request $request){

      if($request->has('order_id')){

          $user_id=Auth::user()->id;
          $order_id=$request->order_id;
          $order = Order::find($order_id);
          $order->status = 6;
          $order->updated_at = new \DateTime();
          $order->save();

          $task_id=$order->task_id;
          $task = Task::find($task_id);
          $task->status = 1;
          $task->user_id = null;
          $task->order_id = null;
          $task->save();

          // записуем в лог
          Log::write(18, $task_id, $order->id,$user_id , null);

          // обработать заказы который шли добавочно
          $sub_orders = DB::table('orders')
              ->where('parent_id', $order_id)
              ->get();
          if ($sub_orders) {
              foreach ($sub_orders as $sub_order) {
                  $suborder = Order::find($sub_order->id);
                  $suborder->status = 6;
                  $suborder->save();
                  // ставим статус свободно
                  $sub_task = Task::find($sub_order->task_id);
                  $sub_task->status = 1;
                  $sub_task->save();
                  // записуем в лог
                  Log::write(18, $sub_order->task_id, $sub_order->id, $user_id, null );
              }
          }
          return response()->json([
              'success' => true,
          ], 200);
      }
    }
    // установка массово значение ошибка
    public function FieldArray(Request $request){
        $ids = $request->input('ids');
        $comment = serialize(
            [
                'comment' => $request->input('comment'),
                'select' => $request->input('select'),
            ]
        );
        $status=4;
        foreach ($ids as $id){
            $log_data = [];

            $order = Order::find($id);
            $order->status = $status;
            $order->comment = $comment;
            $order->updated_at = new \DateTime();
            $order->save();

            $task_id=$order->task_id;
            $task = Task::find($task_id);
            $task->status = 4;
            $task->save();
            // записуем в лог
            Log::write(3, $task_id, $order->id, $order->user_id, null,
                [
                    'comment' => $request->input('comment'),
                    'select' => $request->input('select'),
                ]
            );
            // обработать заказы который шли добавочно
            $sub_orders = DB::table('orders')
                ->where('parent_id', $id)
                ->get();
            if ($sub_orders) {
                foreach ($sub_orders as $sub_order) {

                    $suborder = Order::find($sub_order->id);
                    $suborder->status = 4;
                    $suborder->save();
                    // ставим статус свободно
                    $sub_task = Task::find($sub_order->task_id);
                    $sub_task->status = $status;
                    $sub_task->save();
                    // записуем в лог
                    Log::write(3, $sub_order->task_id, $sub_order->id, $order->user_id, null,
                        [
                            'parenttask' => 'Add in addition to task:' . $task_id,
                            'comment' => $request->input('comment'),
                            'select' => $request->input('select'),
                        ]
                    );

                }
            }
        }
        return response()->json([
            'success' => true,
        ], 200);

    }

    // получить заказ для редактировани
    public function thisUserOrder(Request $request)
    {

        $order_id = $request->input('order_id');
        $order = Order::find($order_id);

        if ($order->status == 4 && !empty($order->comment)) {
            $comment = unserialize($order->comment);
            $order['commentFailed'] = $comment['comment'];
            $order['selectFailed'] = $comment["select"];
        }
        $order['task'] = $order->task;
        $order['serials'] = $order->serials;

        return response()->json([
            'success' => true,
            'order' => $order,
            'failed' => config('status_coments.failed'),
        ], 200);

    }



    //обновить заказ при редактировании
    public function UpdateUserOrder(Request $request)
    {
        $type = $request->input('type');
        switch ($type) {

            case 'updateFailed':
                OrderUpdateService::updateFailed($request->all());
                break;

            case 'changeFailed':
                OrderUpdateService::changeFailed($request->all());
                break;

            case 'updateDone':
                OrderUpdateService::updateDone($request->all());
                break;

            case 'changeDone':
                OrderUpdateService::changeDone($request->all());
                break;

        }
        return response()->json([
            'success' => true,
        ], 200);

    }

}
