<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Task;
class TasklistuserController extends Controller
{

    public function index(){

        return view('task.tasklistuser',[
            'title'=>trans('task.title'),
            'meta_title'=>trans('task.meta_title'),
            'with_sidebar'=>false,
            'with_content'=>'12'
        ]);

    }

    public function taskslistuserfree(){

        return view('task.taskslistuserfree',[
            'title'=>trans('task.free'),
            'meta_title'=>trans('task.meta_free'),
            'with_sidebar'=>false,
            'with_content'=>'12'
        ]);

    }


}
