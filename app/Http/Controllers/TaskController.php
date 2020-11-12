<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Task;
use \App\User;

class TaskController extends Controller
{
    public function index()
    {
            $users = User::where('role', 3)->get();
            return view('task.taskListReadAdmin', [
                'title' => trans('task.title_read'),
                'meta_title' => trans('task.title_read'),
                'with_sidebar' => false,
                'with_content' => '12',
                'users' => $users
            ]);
    }

    public function listing(Request $request){
        $users = User::where('role', 3)->get();
        return view('task.taskListAdmin', [
            'title' => trans('task.title'),
            'meta_title' => trans('task.meta_title'),
            'with_sidebar' => false,
            'with_content' => '12',
            'users' => $users
        ]);
    }
}
