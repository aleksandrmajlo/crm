<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Task;
class TaskController extends Controller
{
    public function index(){

        return view('task.task',[
            'title'=>trans('task.title'),
            'meta_title'=>trans('task.meta_title'),
        ]);
    }


}
