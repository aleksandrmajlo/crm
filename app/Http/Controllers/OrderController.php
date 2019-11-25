<?php

namespace App\Http\Controllers;

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

    // аяксом данные
    public function orderLogAjax(Request $request)
    {
//        ini_set('display_errors', 1);
//        ini_set('display_startup_errors', 1);
//        error_reporting(E_ALL);

        $task_id = $request->task_id;

//        $task_id=2440;
        $orderlogs = Orderlog::where('task_id', $task_id)->orderBy("created_at", 'desc')->get();
        $results = [];
        $status = config('adm_settings.LogStatus');
        $failed_status = config('status_coments.failed');

        foreach ($orderlogs as $orderlog) {
            $failed = [
                'failedstatus' => '',
                'comment' => ''
            ];
            $doneData = [
                'serials' => [],
                'commentall' => ''
            ];
            if ($orderlog->status == 4) {
                $failed['failedstatus'] = $failed_status[$orderlog->failedstatus];
                $failed['comment'] = $orderlog->comment;
            }

            if ($orderlog->status == 3) {
                $done = unserialize($orderlog->done);
                if(isset($done['commentall'])){
                    $doneData['commentall'] = $done['commentall'];
                }
                $doneData['serials'] = $done['serials'];
            }
            $user="";
            if($orderlog->user){
                $user=$orderlog->user->email.' '.$orderlog->user->fullname;
            }
            $results[] = [
                'user' => $user,
                'status' => $status[$orderlog->status],
                'date' => $orderlog->created_at->format('Y-m-d H:i:s'),
                'failed' => $failed,
                'doneData' => $doneData
            ];

        }
        return response()->json([
            'success' => true,
            'orderlogs' => $results,

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
//        $task->admincomments->save($admincomment);

        /*
        $order = Order::find($order_id);
        if ($order->admincomment) {
            $admincomment = Admincomment::find($order->admincomment->id);
        } else {
            $admincomment = new Admincomment;
        }
        $admincomment->commentadmin = $request->commentadmin;
        $admincomment->showcommentadmin = $request->showcommentadmin;
        $admincomment->order_id = $order_id;
        $admincomment->user_id = $order->user_id;
        $admincomment->task_id = $order->task_id;
        $admincomment->save();
        */

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