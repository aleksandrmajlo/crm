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
    public static function getOrderActive(){
        $user_id = Auth::user()->id;
        return DB::table('orders')
            ->leftJoin('tasks', 'orders.task_id', '=', 'tasks.id')
            ->where('orders.user_id',$user_id)
            ->where('orders.status',1)
            ->select(
                'orders.id',
                'orders.task_id',
                'tasks.ip',
                'tasks.port',
                'tasks.port',
                'tasks.domain',
                'tasks.login',
                'tasks.password',
                'tasks.weight'
            )
            ->get();

    }
    public static function getCountOrderUser($user_id){

        // статусы заказов
        // status 1 - в работе
        // status 2 -  завершено
        // type 1 - основное
        // type 2 - дополнительное
        return DB::table('orders')
            ->where('user_id',$user_id)
            ->where('status',1)
            ->where('type',1)
            ->count();
    }
    // история заказов
    public static function getOrderHistory(){

    }

}