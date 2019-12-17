<?php

namespace App\Observers;

use App\Services\TaskService;
use App\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag;

class TaskObserver
{
    /**
     * Handle the task "created" event.
     *
     * @param  \App\Task $task
     * @return void
     */
    public function creating(Task $task)
    {
        $duplicate=Task::where('ip',$task->ip)
              ->where('port',$task->port)
              ->count();
        if($duplicate>0){
            $error = new MessageBag([
                'title'   => 'Duplicate',
                'message' => 'Duplicate IP: '.$task->ip.'  Port: '.$task->port
            ]);
            return back()->with(compact('error'));
        }
        $task->flag = $this->getFlag($task->ip);
    }

    public function created(Task $task)
    {

    }

    /**
     * Handle the task "updated" event.
     *
     * @param  \App\Task $task
     * @return void
     */
    public function updating(Task $task)

    {
        $task->flag = $this->getFlag($task->ip);
    }

    /**
     * Handle the task "deleted" event.
     *
     * @param  \App\Task $task
     * @return void
     */
    public function deleted(Task $task)
    {
        //
    }

    public function deleting(Task $task)
    {
        if($task->order){
            $task->order->delete();
        }

        //комменты удаляем
        if($task->admincomments){
            foreach ($task->admincomments as $admincomment){
                $admincomment->delete();
            }
        }
        $tasklogs=DB::table('tasklogs')->where('task_id', $task->id)->get();
        if($tasklogs){
            foreach ($tasklogs as $tasklog){
                DB::table('tasklogs')->where('id', '=', $tasklog->id)->delete();
            }
        }

        //лог удаляем
        if($task->orderlogs){
            foreach ($task->orderlogs as $orderlog){
                $orderlog->delete();
            }
        }


    }

    /**
     * Handle the task "restored" event.
     *
     * @param  \App\Task $task
     * @return void
     */
    public function restored(Task $task)
    {
        //
    }

    /**
     * Handle the task "force deleted" event.
     *
     * @param  \App\Task $task
     * @return void
     */
    public function forceDeleted(Task $task)
    {
        //
    }

    public function getFlag($ip)
    {
        $IP_DATA_KEY = env('IP_DATA_KEY');

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.ipdata.co/" . $ip . "?api-key=".$IP_DATA_KEY,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $response_ar = json_decode($response);
        if ($err) {
            return "";
        } else {
            if(isset($response_ar->flag)){
                return $response_ar->flag;
            }else{
                return "";
            }
        }
    }


}
