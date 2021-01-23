<?php

namespace App\Http\Controllers;

use App\Services\Log;
use App\Services\Logwrite;
use App\User;
use Illuminate\Http\Request;
use \App\Task;
use \App\Order;
use \App\Orderlog;
use \App\Admincomment;

use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $limit=20;
    public function orderLog(Request $request)
    {

        if (Auth::user()->role == 3) {
            return redirect('/noaccess');
        } else {

            $results=[];
            $orderlogs=Orderlog::orderBy("created_at",'DESC')->paginate($this->limit);
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
                    $text=@unserialize($orderlog->text);
                }
                $created_at=$orderlog->created_at;
                $results[]=[
                    'log_id'=>$orderlog->id,
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
            return view(
                'order.orderLogAdmin',[
                    'title' => trans('order.orderfailedTitle'),
                    'meta_title' => trans('order.orderfailedTitle'),
                    'orderlogs' => $results,
                    'data_orderlogs' =>$orderlogs,
                    'failed_status' => config('status_coments.failed'),
                    'status' => config('adm_settings.LogStatus'),
                    'with_sidebar' => false,
                    'with_content' => '12'
                ]
            );
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

}
