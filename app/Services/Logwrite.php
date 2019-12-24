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

    public static function all(){
        $results=[];
        $orderlogs=Orderlog::orderBy("created_at",'DESC')->get();
        $log_config=config('log');
        $failed_status = config('status_coments.failed');

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
            $failedStatus='';
            if($orderlog->text){
                $text=unserialize($orderlog->text);
            }
            $created_at=$orderlog->created_at;
            $results[]=[
                'id'=>$orderlog->task->id,
                'status'=>$log_config[$orderlog->status],
                'status_id'=>$orderlog->status,
                'ip'=>$orderlog->task->ip,
                'port'=>$orderlog->task->port,
                'user_id'=>$user_id,
                'admin_id'=>$admin_id,
                'text'=>$text,
                'created_at'=>$created_at,
                'failedStatus'=>$failedStatus
            ];
        }
        return $results;
    }

    public static function write($id,$ajax=false){
        $results=[];
        $orderlogs=Orderlog::where('task_id',$id)->orderBy("created_at",'DESC')->get();

        $log_config=config('log');
        $task=Task::find($id);
        $failed_status = config('status_coments.failed');
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
            $failedStatus='';
            if($orderlog->text){
                $text=unserialize($orderlog->text);
                if($ajax){

                   if($orderlog->status==3||$orderlog->status==5||$orderlog->status==6){
                       $failedStatus=$failed_status[$text['select']];
                   }

                }
            }

            $created_at=$orderlog->created_at;
            if($ajax){
                $created_at=$orderlog->created_at->format('Y-m-d H:i:s');
            }
            $results[]=[
                'status'=>$log_config[$orderlog->status],
                'status_id'=>$orderlog->status,
                'ip'=>$task->ip,
                'port'=>$task->port,
                'user_id'=>$user_id,
                'admin_id'=>$admin_id,
                'text'=>$text,
                'created_at'=>$created_at,
                'failedStatus'=>$failedStatus
            ];
        }
        return $results;
    }


}