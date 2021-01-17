<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use \App\Task;
use \App\User;
use Illuminate\Support\Facades\DB;

class TaskController extends Controller
{

    public function index(Request $request)
    {
        $dates = DB::table('tasks')
            ->orderBy('created_at', 'DESC')
            ->get('created_at')
            ->groupBy(function ($val) {
                return Carbon::parse($val->created_at)->format('Y-m-d');
            });
        $date=false;
        if ($request->has('date')) {
            $date=$request->date;
        }
        $users = User::where('role', 3)->get();
        return view('task.taskListReadAdmin', [
            'title' => trans('task.title_read'),
            'meta_title' => trans('task.title_read'),
            'with_sidebar' => false,
            'with_content' => '12',
            'users' => $users,
            'dates' => $dates,
            'date'=>$date
        ]);

    }

    public function listing(Request $request)
    {
        $users = User::where('role', 3)->get();
        $paginate = config('custom.paginate');
        $tasks = Task::orderBy('id', 'DESC')->paginate($paginate);


        $dates = DB::table('tasks')
            ->orderBy('created_at', 'DESC')
            ->get('created_at')
            ->groupBy(function ($val) {
                return Carbon::parse($val->created_at)->format('Y-m-d');
            });
        return view('task.taskListAdmin', [
            'title' => trans('task.title'),
            'meta_title' => trans('task.meta_title'),
            'with_sidebar' => false,
            'with_content' => '12',
            'tasks' => $tasks,
            'users' => $users,
            'dates' => $dates,
            'status' => config('adm_settings.LogStatus')
        ]);
    }

}
