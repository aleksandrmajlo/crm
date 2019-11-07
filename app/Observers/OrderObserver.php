<?php

namespace App\Observers;

use App\Order;

class OrderObserver
{
    /**
     * Handle the order "created" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function created(Order $order)
    {
        //
    }

    /**
     * Handle the order "updated" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function updated(Order $order)
    {
        //
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function deleted(Order $order)
    {
        //
    }

    public function deleting(Order $order)
    {
        $order_id=$order->id;
        $task_id=$order->task_id;
        if($order->task){

            $task=\App\Task::findOrFail($task_id);
            $task->order_id=null;
            $task->user_id=null;
            $task->status=1;
            $task->save();
        }
        // cерийники удаляем
        $serials=\App\Serial::where('order_id',$order_id)->get();
        if($serials){
            foreach ($serials as $serial){
                $serial->delete();
            }
        }
        //лог удаляем
        if($order->orderlogs){
            foreach ($order->orderlogs as $orderlog){
                $orderlog->delete();
            }
        }

    }

    /**
     * Handle the order "restored" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function restored(Order $order)
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param  \App\Order  $order
     * @return void
     */
    public function forceDeleted(Order $order)
    {
        //
    }
}
