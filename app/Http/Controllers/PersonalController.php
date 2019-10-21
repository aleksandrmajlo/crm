<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function editOrder(Request $request){
        if ($request->has('order')){
            $order_id=$request->input('order');
            dd($order_id);
        }else{
            abort(404);
        }
    }
}
