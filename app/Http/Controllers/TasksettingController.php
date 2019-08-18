<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class TasksettingController extends Controller
{
    public function index(){

        if(Auth::user()->role!==1){
            return  redirect('/noaccess');
        }else{
            return view('task.tasksetting',[
                'title'=>'Import ',
                'meta_title'=>'Import',
                'with_sidebar'=>true
            ]);
        }

    }
}
