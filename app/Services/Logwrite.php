<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 14.12.2019
 * Time: 16:09
 */

namespace App\Services;
use App\Orderlog;
use App\Task;
use App\User;


class Logwrite
{
    public static function write($id){
        $results=[];
        $orderlogs=Orderlog::where('task_id',$id)->orderBy("created_at",'DESC')->get();

        $log_config=config('log');
        $task=Task::find($id);
        foreach ($orderlogs as $orderlog){

            $user_id=false;
            if($orderlog->user_id){
                $user=User::find($orderlog->user_id);
                $user_id=$user->email.' '.$user->name;
            }

            $admin_id=false;
            if($orderlog->admin_id){
                $user=User::find($orderlog->admin_id);
                $admin_id=$user->email.' '.$user->name;
            }
            $text=false;
            if($orderlog->text){
                $text=unserialize($orderlog->text);
            }

            $results[]=[
                'status'=>$log_config[$orderlog->status],
                'status_id'=>$orderlog->status,
                'ip'=>$task->ip,
                'port'=>$task->port,
                'user_id'=>$user_id,
                'admin_id'=>$admin_id,
                'text'=>$text,
                'created_at'=>$orderlog->created_at
            ];
        }
//        dump($log_config);
        return $results;
    }
}