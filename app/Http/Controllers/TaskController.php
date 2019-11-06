<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Task;
use \App\User;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index()
    {
        if (Auth::user()->role == 3) {
            return redirect('/noaccess');
        } else {
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
}
