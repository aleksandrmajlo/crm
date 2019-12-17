<?php

namespace App\Http\Controllers;

use App\Services\Log;
use App\Services\Logwrite;
use Illuminate\Http\Request;
use \App\Task;
use \App\Order;
use \App\Orderlog;
use \App\Admincomment;

use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function orderLog(Request $request)
    {

        if (Auth::user()->role == 3) {
            return redirect('/noaccess');
        } else {
            $orderfaileds = Orderlog::all()->sortByDesc("created_at");
            return view('order.orderLogAdmin', [
                'title' => trans('order.orderfailedTitle'),
                'meta_title' => trans('order.orderfailedTitle'),
                'orderfaileds' => $orderfaileds,
                'failed_status' => config('status_coments.failed'),
                'status' => config('adm_settings.LogStatus'),
                'with_sidebar' => false,
                'with_content' => '12'
            ]);
        }
    }

    // поиск по ID в логе
    public function orderLogID(Request $request)
    {

        if (Auth::user()->role == 3) {
            return redirect('/noaccess');
        } else {
            $orderlogs=false;
            $value="";
            if($request->has('id')){
                $value=$request->id;
                $orderlogs = Logwrite::write($request->id);
            }
            return view('order.orderLogAdminSearchID', [
                'title' => trans('order.orderLogAdminSearchID'),
                'meta_title' => trans('order.orderLogAdminSearchID'),
                'orderlogs' => $orderlogs,
                'value' =>$value,
                'failed_status' => config('status_coments.failed'),
                'with_sidebar' => false,
                'with_content' => '12'
            ]);
        }
    }

    // аяксом данные
    public function orderLogAjax(Request $request)
    {

        $results = [];
        $orderlogs = Logwrite::write($request->task_id,true);
        foreach ($orderlogs as $orderlog){
            $results[]=[

            ];
        }
        return response()->json([
            'success' => true,
            'orderlogs' => $orderlogs,

        ], 200);
    }

    // добавляем комментарий
    public function orderCommentAdmin(Request $request)
    {

        $user_id=Auth::user()->id;
        $task_id = $request->input('task_id');
        $task=Task::find($task_id);
        $order_id=0;
        if(isset($task->order)){
            $order_id=$task->order->id;
        }

        $admincomment =new Admincomment;

        $admincomment->commentadmin=$request->text;
        $admincomment->showcommentadmin=$request->showcommentadmin;
        $admincomment->user_id      =$user_id;
        $admincomment->order_id  = $order_id;
        $admincomment->task_id  =$task_id;
        $admincomment->save();

        Log::write(11,$task_id,$order_id,$task->user_id,$user_id,
            [
                'comment'=>$request->text,
                'showcommentadmin'=>$request->showcommentadmin
            ]
            );

        return response()->json([
            'success' => true,
        ], 200);
    }

    public function Get_taskcomment(Request $request){

        $results=[];
        $task_id = $request->input('task_id');
        $task=Task::find($task_id);
        if($task->admincomments){
            foreach ($task->admincomments as $admincomment){
                $admin="";
                if($admincomment->user){
                    $admin=$admincomment->user->email.' '.$admincomment->user->fullname;
                }
                $results[]=[
                    'text'=>$admincomment->commentadmin,
                     'date'=>$admincomment->created_at->format('Y-m-d H:i:s'),
                    'admin'=>$admin
                ];
            }
        }
        return response()->json([
            'results'=>$results,
            'success' => true,
        ], 200);
    }

}