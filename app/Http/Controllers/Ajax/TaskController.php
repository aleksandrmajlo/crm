<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Task;
use App\Services\OrderService;
class TaskController extends Controller
{

    public function get(){
        $tasks = Task::where('status',1)->get(['id','weight','status','flag']);
        $userOrder=OrderService::getOrderActive();
        return response()->json([
            'tasks' => $tasks,
            'myorder'=>$userOrder->pluck('task_id')->toArray()
        ], 200);
    }
    public function one(Request $request){
         $id=$request->id;
         $task=Task::findOrFail($id);
         if($task->status==1){
             //5.15.20.34:1234@domain\login;password
             $str=$task->ip.':'.$task->port.'@'.$task->domain.'\\'.$task->login.';'.$task->password;
             return response()->json(['task' => $str], 200);
         }else{
             return response()->json(['engaged' => true], 200);
         }
    }
}
