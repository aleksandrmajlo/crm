<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 27.08.2019
 * Time: 13:36
 */

namespace App\Services;

use App\Task;
use App\Order;
use Illuminate\Support\Facades\DB;


class CommentService
{
    public static function get($task_id){

        $results=[];

        $order = DB::table('orders')
            ->leftJoin('tasks', 'orders.task_id', '=', 'tasks.id')
            ->where('orders.task_id', $task_id)
            ->select(
                'orders.id',
                'orders.task_id',
                'orders.type',
                'orders.parent_id',
                'orders.created_at',
                'orders.updated_at',
                'orders.comment',
                'tasks.ip',
                'tasks.port',
                'tasks.domain',
                'tasks.login',
                'tasks.password',
                'tasks.flag',
                'tasks.weight'
            )
            ->first();

        if($order){
            $results['ip']=$order->ip;
            $results['port']=$order->port;
            $results['domain']=$order->domain;
            $results['login']=$order->login;
            $results['password']=$order->password;
            $results['weight']=$order->weight;
            $results['created_at']=$order->created_at;
            $results['updated_at']=$order->updated_at;
            if($order->type==1){
                $results['comment']=unserialize($order->comment);
            }else{
                $parent_id=$order->parent_id;
                $parent_order = DB::table('orders')
                    ->where('id', $parent_id)
                    ->first();
                $results['comment']=unserialize($parent_order->comment);
            }
        }
        return $results;
    }
}