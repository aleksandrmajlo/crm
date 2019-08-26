<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 11.08.2019
 * Time: 17:22
 */

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService
{
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
    public static function getLimitUser()
    {
        $user = Auth::user();
        $weight_all = 0;
        $orders = DB::table('orders')
            ->leftJoin('tasks', 'orders.task_id', '=', 'tasks.id')
            ->where('orders.user_id', $user->id)
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
            ->where('orders.user_id', $user_id)
            ->where('orders.type', 1)
            ->where('orders.status', 3)
            ->Orwhere('orders.status', 4)
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
                    'orders.status',
                    'tasks.ip',
                    'tasks.port',
                    'tasks.domain',
                    'tasks.login',
                    'tasks.password',
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
                'created_at'=>\Carbon\Carbon::parse($order->created_at)->timestamp,
                'updated_at'=>\Carbon\Carbon::parse($order->updated_at)->timestamp,
                'sub_orders'=>$ar_sub_orders
            ];
        }

        return $results;
    }


}