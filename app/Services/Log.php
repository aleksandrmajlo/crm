<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 05.11.2019
 * Time: 14:31
 */

namespace App\Services;

use \App\Orderlog;

class Log
{

    /*
     * $order model
     */
    static public function write($order,$status,$user_id=null,$log_data=false){

        $orderlog = new Orderlog;
        $orderlog->order_id = $order->id;
        $orderlog->task_id = $order->task_id;
        $orderlog->user_id = $user_id;
        $orderlog->status = $status;
        if($log_data){
            if(isset($log_data['falied'])){
                $orderlog->failedstatus=$log_data['falied']['failedstatus'];
                $orderlog->comment=$log_data['falied']['comment'];
            }
            if(isset($log_data['done'])){
                $orderlog->done=serialize($log_data['done']);
            }
        }
        $orderlog->save();

    }

}