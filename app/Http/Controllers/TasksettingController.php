<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class TasksettingController extends Controller
{
    public function index(){

        $user=Auth::user();
        if($user->role==1||($user->role==3&&$user->download==1)){
            return view('task.tasksetting',[
                'title'=>'Import ',
                'meta_title'=>'Import',
                'with_sidebar'=>true
            ]);
        }else{
            return  redirect('/noaccess');

        }

    }
}
