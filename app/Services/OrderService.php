<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 11.08.2019
 * Time: 17:22
 */

namespace App\Services;

use App\Task;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\Log;

class OrderService
{
    // добавить заказ
    public static function add($task, $user_id){
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
        Log::write($order, 2, $user_id);
        // вставляем заказ
        $task->status = 2;
        $task->user_id = $user_id;
        $task->save();

        $ids[] = $task->id;
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
                    $order->type = 1;
                    $order->save();
                    $id_other = $order->id;
                }
                $task_otner->status = 2;
                $task_otner->user_id = $user_id;
                $task_otner->save();
                $ids[] = $task_otner->id;
                // записуем в лог
                Log::write($order, 2, $user_id);
            }
        }

    }

    public static function getOrderActive($user_id)
    {
        $results = [];
        $orders = DB::table('orders')
            ->leftJoin('tasks', 'orders.task_id', '=', 'tasks.id')
            ->where('orders.user_id', $user_id)
            ->where('orders.status', 1)
            ->where('orders.type', 1)
            ->select(
                'orders.id',
                'orders.task_id',
                'tasks.ip',
                'tasks.port',
                'tasks.domain',
                'tasks.login',
                'tasks.password',
                'tasks.flag',
                'tasks.weight'
            )
            ->get();
        foreach ($orders as $order) {
            $sub_orders = DB::table('orders')
                ->leftJoin('tasks', 'orders.task_id', '=', 'tasks.id')
                ->where('orders.parent_id', $order->id)
                ->select(
                    'orders.id',
                    'orders.task_id',
                    'tasks.ip',
                    'tasks.port',
                    'tasks.domain',
                    'tasks.login',
                    'tasks.password',
                    'tasks.flag',
                    'tasks.weight'
                )
                ->get();
            $ar_sub_orders = [];
            foreach ($sub_orders as $sub_order) {
                $ar_sub_orders[]=[
                    'id'=>$sub_order->id,
                    'task_id'=>$sub_order->task_id,
                    'ip'=>$sub_order->ip,
                    'port'=>$sub_order->port,
                    'domain'=>$sub_order->domain,
                    'login'=>$order->login,
                    'weight'=>$order->weight,
                    'password'=>$sub_order->password,
                    'flag'=>$sub_order->flag,
                ];

            }
            $results[]=[
                'id'=>$order->id,
                'task_id'=>$order->task_id,
                'ip'=>$order->ip,
                'port'=>$order->port,
                'domain'=>$order->domain,
                'login'=>$order->login,
                'password'=>$order->password,
                'weight'=>$order->weight,
                'flag'=>$order->flag,
                'sub_orders'=>$ar_sub_orders
            ];
        }
        return $results;
    }

    // проверка при заказе или доступен заказ
    public static function getWeigthOrderUser($user_id, $task_weight, $user_weight)
    {
        // статусы заказов
        // status 1 - в работе
        // status 3 -  завершено положительно
        // status 4 -  завершено отрицательно

        // type 1 - основное
        // type 2 - дополнительное
        $weight_all = 0;
        $orders = DB::table('orders')
            ->leftJoin('tasks', 'orders.task_id', '=', 'tasks.id')
            ->where('orders.user_id', $user_id)
            ->where('orders.status', 1)
            ->where('orders.type', 1)
            ->select(
                'orders.id',
                'tasks.weight'
            )->get();
        foreach ($orders as $order) {
            $weight_all += floatval($order->weight);
        }
        $weight_all += $task_weight;
        if (floatval($user_weight) >= $weight_all) {
            return true;
        } else {
            return false;
        }

    }
    // получить лимит
    public static function getLimitUser($id=false)
    {
        if(!$id){
            $user = Auth::user();
            $id=$user->id;
        }
        $weight_all = 0;
        $orders = DB::table('orders')
            ->leftJoin('tasks', 'orders.task_id', '=', 'tasks.id')
            ->where('orders.user_id', $id)
            ->where('orders.status', 1)
            ->where('orders.type', 1)
            ->select(
                'orders.id',
                'tasks.weight'
            )->get();
        foreach ($orders as $order) {
            $weight_all += floatval($order->weight);
        }
        return $weight_all;
    }
    // история заказов
    public static function getOrderHistory($user_id)
    {
        $results = [];

        $orders = DB::table('orders')
            ->leftJoin('tasks', 'orders.task_id', '=', 'tasks.id')
            ->leftJoin('admincomments', 'orders.id', '=', 'admincomments.order_id')
            ->where('orders.user_id', $user_id)
            ->where('orders.type', 1)
            ->where('orders.status','>',2 )
            ->select(
                'orders.id',
                'orders.task_id',
                'orders.status',
                'orders.created_at',
                'orders.updated_at',
                'tasks.ip',
                'tasks.port',
                'tasks.domain',
                'tasks.login',
                'tasks.password',
                'tasks.flag',
                'tasks.weight',
                'admincomments.commentadmin',
                'admincomments.showcommentadmin'
            )
            ->get();
        foreach ($orders as $order) {
            $sub_orders = DB::table('orders')
                ->leftJoin('tasks', 'orders.task_id', '=', 'tasks.id')
                ->where('orders.parent_id', $order->id)
                ->select(
                    'orders.id',
                    'orders.task_id',
                    'orders.status',
                    'tasks.ip',
                    'tasks.port',
                    'tasks.domain',
                    'tasks.login',
                    'tasks.password',
                    'tasks.flag',
                    'tasks.weight'
                )
                ->get();
            $ar_sub_orders = [];
            foreach ($sub_orders as $sub_order) {
                $ar_sub_orders[]=[
                    'id'=>$sub_order->id,
                    'task_id'=>$sub_order->task_id,
                    'ip'=>$sub_order->ip,
                    'port'=>$sub_order->port,
                    'domain'=>$sub_order->domain,
                    'login'=>$order->login,
                    'weight'=>'Parent ID '.$order->task_id,
                    'password'=>$sub_order->password,
                    'flag'=>$sub_order->flag,
                    'created_at'=>'Parent ID '.$order->task_id,
                    'updated_at'=>'Parent ID '.$order->task_id,
                    'status'=>$order->status
                ];
            }
            $results[]=[
                'id'=>$order->id,
                'task_id'=>$order->task_id,
                'ip'=>$order->ip,
                'port'=>$order->port,
                'domain'=>$order->domain,
                'login'=>$order->login,
                'password'=>$order->password,
                'weight'=>$order->weight,
                'status'=>$order->status,
                'flag'=>$order->flag,
                'commentadmin'=>$order->commentadmin,
                'showcommentadmin'=>$order->showcommentadmin,
                'created_at'=>\Carbon\Carbon::parse($order->created_at)->timestamp,
                'updated_at'=>\Carbon\Carbon::parse($order->updated_at)->timestamp,
                'sub_orders'=>$ar_sub_orders
            ];
        }

        return $results;
    }


}