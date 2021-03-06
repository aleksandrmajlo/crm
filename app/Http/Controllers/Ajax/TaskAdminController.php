<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Task;
use App\Services\OrderService;

use Illuminate\Support\Carbon;

class TaskAdminController extends Controller
{

    // получить список заданий всех для админа
    public function get(Request $request){

        $results=[];
        $read_tasks=[];
        $tasks = Task::whereDate('created_at', $request->date)->orderBy('id','desc')->get();
        if($tasks){
            foreach ($tasks as $task){

                if(isset($task->user->name)){
                    $username=$task->user->name;
                }else{
                    $username=false;
                }

                if(isset($task->user->email)){
                    $useremail=$task->user->email;
                }else{
                    $useremail=false;
                }
                $year = $task->created_at->year;
                $month = $task->created_at->month;
                $date = Carbon::parse($task->created_at)->format('d');
                $timestamp =strtotime($task->created_at->format( $year.'-'.$month.'-'.$date)) ;
                if(!isset($results[$timestamp])){
                    $results[$timestamp]=[];
                }
                $results[$timestamp][]=[
                    'id'=>$task->id,
                    'ip'=>$task->ip,
                    'port'=>$task->port,
                    'domain'=>$task->domain,
                    'login'=>$task->login,
                    'password'=>$task->password,
                    'weight'=>$task->weight,
                    'status'=>$task->status,
                    'user_id'=>$task->user_id,
                    'flag'=>$task->flag,
                    'created_at'=>$task->created_at,
                    'timestamp'=>$timestamp,
                    'color'=>$task->color,
                    'username'=>$username,
                    'useremail'=>$useremail,
                    'countComments'=>count($task->admincomments)
                ];
            }
            return response()->json([
                'success'=>true,
                'read_tasks'=>$results,
                'status'=>config('adm_settings.LogStatus')
            ], 200);

        }
        else{
            return response()->json([
                'success'=>false,
            ], 200);
        }
    }
    // получuить обновленное задание
    public function getTask(Request $request){

        $task_id=$request->task_id;

        $task = Task::findOrFail($task_id);

        $year = $task->created_at->year;
        $month = $task->created_at->month;
        $date = Carbon::parse($task->created_at)->format('d');
        $timestamp =strtotime($task->created_at->format( $year.'-'.$month.'-'.$date)) ;
        if(isset($task->user->name)){
            $username=$task->user->name;
        }else{
            $username=false;
        }

        if(isset($task->user->email)){
            $useremail=$task->user->email;
        }else{
            $useremail=false;
        }

        $result=[
            'id'=>$task->id,
            'ip'=>$task->ip,
            'port'=>$task->port,
            'domain'=>$task->domain,
            'login'=>$task->login,
            'password'=>$task->password,
            'weight'=>$task->weight,
            'status'=>$task->status,
            'user_id'=>$task->user_id,
            'flag'=>$task->flag,
            'created_at'=>$task->created_at,
            'timestamp'=>$timestamp,
            'color'=>$task->color,
            'username'=>$username,
            'useremail'=>$useremail,
            'countComments'=>count($task->admincomments)
        ];
        return response()->json([
            'success'=>true,
            'task'=>$result,
        ], 200);
    }
    // сохранить отредактированные задания
    public function  save(Request $request){
        $ids=$request->input('ids');
        $counter=0;
        foreach ($ids as $id){
            $task=Task::findOrFail($id['id']);
            $task->weight=$id['weight'];
            $task->save();
            $counter++;
        }
        return response()->json([
            'success' => 'Updated '.$counter.' task',
        ], 200);
    }

    // сохранить отредактированное задание одиночное
    public function  SaveOneWeigth(Request $request){
        $id=$request->input('id');
        $val=$request->input('val');
        $task=Task::find($id);
        $task->weight=$val;
        $task->save();
        return response()->json([
            'success' => 'Updated 1 task',
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


