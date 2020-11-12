<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Order;
class PersonalController extends Controller
{
    public function index()
    {

        return view('task.mytask',[
            'title'=>trans('mytask.title'),
            'meta_title'=>trans('mytask.meta_title'),
            'with_sidebar'=>false,
            'with_content'=>'12'
        ]);
    }

    public function History(){
        return view('task.history',[
            'title'=>trans('mytask.title_history'),
            'meta_title'=>trans('mytask.title_history'),
            'with_sidebar'=>false,
            'with_content'=>'12'
        ]);
    }

    public function editOrder(Request $request){
        if ($request->has('order')){
            $order_id=$request->input('order');
            $order=Order::findOrFail($order_id);
            $user_id=Auth::user()->id;
            if($order->user_id!==$user_id){
                abort(404);
            }
            return view('order.read',[
                'title'=>'Read Order '.$order->task_id,
                'meta_title'=>'Read Order ',
                'with_sidebar'=>false,
                'with_content'=>'12',
                'order'=>$order,
                'order_id' =>$order_id
            ]);
        }else{
            abort(404);
        }
    }
}
