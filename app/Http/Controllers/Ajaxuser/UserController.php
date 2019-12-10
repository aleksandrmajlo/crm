<?php
/**
 * Created by PhpStorm.
 * User: aleks
 * Date: 17.08.2019
 * Time: 23:03
 */

namespace App\Http\Controllers\Ajaxuser;


use App\Http\Controllers\Controller;
use App\Admincomment;
use App\Order;
use App\Task;
use App\Services\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;


class UserController  extends Controller
{
    public function get(){
        $limit_used=OrderService::getLimitUser();
        return response()->json([
            'success'=>true,
            'user'=>Auth::user(),
            'limit_used'=>$limit_used,
            'status'=>config('adm_settings.LogStatus')
        ], 200);
    }

    public function tasks(){
        $tasks=Task::where('status','<',3)->orderBy('id', 'desc')->get();
        $results=[];
        // для поиска
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


    // установить комментарий просмотреным
    public function CommentViewed(Request $request){
        $comment_id=$request->comment_id;
        $admincomment= Admincomment::find($comment_id);
        if($admincomment->viewed===0){
            $admincomment->viewed=1;
            $admincomment->save();
        }
        return response()->json([
            'success'=>true,
        ], 200);
    }

    // получить сообщение о добавленных заданиях
    public function messange(){

        $user_id=Auth::user()->id;
        $html="";

        $messanges=DB::table('tasklogs')->where('user_id',$user_id)
                                  ->where('messange',0)->get();
        if($messanges){
            foreach ($messanges as $messange){
                $task_id=$messange->task_id;
                $task=Task::find($task_id);
                $order=Order::find($messange->order_id);
                $parent_id=$order->parent_id;
                $parentOrder=Order::find($parent_id);
                if($task->status==2){
                    $html.='<p class="mb-2">
                               Add ID:<span class="text-bold">'.$task_id.' </span> to ID:<span class="text-bold">'.$parentOrder->task_id.'.</span><br>
                               Status:<span class="text-bold">Work</span>
                               </hr>
                           </p>';
                }
            }
        }
        return response()->json([
            'html'=>$html,
            'success'=>true,
        ], 200);

    }

    public function messangeReadStaus(){
        $user_id=Auth::user()->id;
        DB::table('tasklogs')->where('user_id',$user_id)
            ->where('messange',0)
            ->update(['messange' => 1]);
        return response()->json([
            'success'=>true,
        ], 200);

    }

}