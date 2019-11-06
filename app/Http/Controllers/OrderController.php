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
    public function orderLog(Request $request){
        if(Auth::user()->role ==3){
            return  redirect('/noaccess');
        }else{

            $orderfaileds=Orderlog::all()->sortByDesc("created_at");;
//            dd($orderfaileds);
            return view('order.orderLogAdmin',[
                'title'=>trans('order.orderfailedTitle'),
                'meta_title'=>trans('order.orderfailedTitle'),
                'orderfaileds'=>$orderfaileds,
                'failed_status' => config('status_coments.failed'),
                'status' => config('adm_settings.statusTask'),
                'with_sidebar'=>false,
                'with_content'=>'12'
            ]);
        }
    }
    // добавляем комментарий
    public function orderCommentAdmin(Request $request){
        $order_id=$request->input('order_id');
        $order= Order::find($order_id);
        if($order->admincomment){
            $admincomment=Admincomment::find($order->admincomment->id);
        }else{
            $admincomment=new Admincomment;
        }
        $admincomment->commentadmin=$request->commentadmin;
        $admincomment->showcommentadmin=$request->showcommentadmin;
        $admincomment->order_id=$order_id;
        $admincomment->user_id=$order->user_id;
        $admincomment->task_id=$order->task_id;
        $admincomment->save();


        return response()->json([
            'success'=>true,
        ], 200);
    }
}