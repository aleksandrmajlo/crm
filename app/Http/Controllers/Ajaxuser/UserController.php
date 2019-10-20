<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 17.08.2019
 * Time: 23:03
 */

namespace App\Http\Controllers\Ajaxuser;

use App\Http\Controllers\Controller;
use App\Task;
use App\Services\OrderService;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;


class UserController  extends Controller
{
    public function get(){
        $limit_used=OrderService::getLimitUser();
        return response()->json([
            'success'=>true,
            'user'=>Auth::user(),
            'limit_used'=>$limit_used,
            'status'=>config('adm_settings.statusTask')
        ], 200);
    }

    public function tasks(){
        $tasks=Task::where('status','<',3)->orderBy('id', 'desc')->get();
        $results=[];
        // для поиска
//        $results['search']=[];
        if($tasks) {
            foreach ($tasks as $task) {
                $year = $task->created_at->year;
                $month = $task->created_at->month;
                $date = Carbon::parse($task->created_at)->format('d');
                $timestamp = strtotime($task->created_at->format($year . '-' . $month . '-' . $date));
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
                    'timestamp'=>$timestamp
                ];
                krsort($results,SORT_NUMERIC);
            }
        }
        return response()->json([
            'tasks'=>$results,
            'success'=>true,
        ], 200);
    }


}